<?php

/**
 * paymill_paymentgateway
 *
 * @author     Copyright (c) 2013 PayIntelligent GmbH (http://www.payintelligent.de)
 * @copyright  Copyright (c) 2013 Paymill GmbH (https://www.paymill.com)
 */
class paymill_paymentgateway extends paymill_paymentgateway_parent implements Services_Paymill_LoggingInterface
{
    private $_apiUrl;

    private $_paymentProcessor;

    private $_fastCheckoutData;

    private $_clients;

    private $_token;

    protected $_responseCodes = array(
        '10001' => 'PAYMILL_10001',
        '10002' => 'PAYMILL_10002',
        '20000' => 'PAYMILL_20000',
        '40000' => 'PAYMILL_40000',
        '40001' => 'PAYMILL_40001',
        '40100' => 'PAYMILL_40100',
        '40101' => 'PAYMILL_40101',
        '40102' => 'PAYMILL_40102',
        '40103' => 'PAYMILL_40103',
        '40104' => 'PAYMILL_40104',
        '40105' => 'PAYMILL_40105',
        '40106' => 'PAYMILL_40106',
        '40200' => 'PAYMILL_40200',
        '40201' => 'PAYMILL_40201',
        '40202' => 'PAYMILL_40202',
        '40300' => 'PAYMILL_40300',
        '40301' => 'PAYMILL_40301',
        '40400' => 'PAYMILL_40400',
        '40401' => 'PAYMILL_40401',
        '40402' => 'PAYMILL_40402',
        '40403' => 'PAYMILL_40403',
        '50000' => 'PAYMILL_50000',
        '50001' => 'PAYMILL_50001',
        '50100' => 'PAYMILL_50100',
        '50101' => 'PAYMILL_50101',
        '50102' => 'PAYMILL_50102',
        '50103' => 'PAYMILL_50103',
        '50104' => 'PAYMILL_50104',
        '50105' => 'PAYMILL_50105',
        '50200' => 'PAYMILL_50200',
        '50201' => 'PAYMILL_50201',
        '50300' => 'PAYMILL_50300',
        '50400' => 'PAYMILL_50400',
        '50500' => 'PAYMILL_50500',
        '50501' => 'PAYMILL_50501',
        '50502' => 'PAYMILL_50502',
        '50600' => 'PAYMILL_50600'
    );

    /**
     * Return message for the given error code
     *
     * @param string $code
     * @return string
     */
    private function _getErrorMessage($code)
    {
        $message = 'PAYMILL_10001';
        if (array_key_exists($code, $this->_responseCodes)) {
            $message = $this->_responseCodes[$code];
        }

        $oxLang = oxRegistry::getLang();
        $errorMessage = $oxLang->translateString($message, $oxLang->getBaseLanguage(), false);
        return $this->paymillConvertToUtf($errorMessage);
    }

    /**
     * @overload
     */
    public function executePayment($dAmount, &$oOrder)
    {
        $oxConfig = oxRegistry::getConfig();
        $oxSession = oxRegistry::getSession();

        if (!in_array($oOrder->oxorder__oxpaymenttype->rawValue, array("paymill_cc", "paymill_elv"))) {
            return parent::executePayment($dAmount, $oOrder);
        }

        if ($oxSession->hasVariable('paymill_token')) {
            $this->_token = $oxSession->getVariable('paymill_token');
        } else {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay("No Token was provided");
            oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment', false);
        }

        $this->getSession()->setVariable("paymill_identifier", time());

        $this->_apiUrl = paymill_util::API_ENDPOINT;

        $this->_iLastErrorNo = null;
        $this->_sLastError = null;

        $this->_initializePaymentProcessor($dAmount, $oOrder);

        if ($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue) === 'cc') {
            $this->_paymentProcessor->setPreAuthAmount((int) $oxSession->getVariable('paymill_authorized_amount'));
        }

        $this->_loadFastCheckoutData();
        $this->_existingClientHandling($oOrder);

        if ($this->_token === 'dummyToken') {
            $prop = 'paymill_fastcheckout__paymentid_' . $this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue);
            $this->_paymentProcessor->setPaymentId(
                $this->_fastCheckoutData->$prop->rawValue
            );
        }
        
        if ($oOrder->oxorder__oxpaymenttype->rawValue === 'paymill_cc') {
            $result = $this->_paymentProcessor->processPayment(!$oxConfig->getShopConfVar('PAYMILL_PREAUTH'));
        } else {
            $result = $this->_paymentProcessor->processPayment();
        }

        $this->log($result ? 'Payment results in success' : 'Payment results in failure', null);

        if ($result) {
            
            $transactionData = array(
                'oxid' =>  $oOrder->oxorder__oxid
            );
            
            $transaction = oxNew('paymill_transaction');
            
            if ($oxConfig->getShopConfVar('PAYMILL_PREAUTH') && $oOrder->oxorder__oxpaymenttype->rawValue !== 'paymill_elv') {
                $transactionData['preauth_id'] = $this->_paymentProcessor->getPreauthId();
            } else {
                $transactionData['transaction_id'] = $this->_paymentProcessor->getTransactionId();
            }
            
            $transaction->assign($transactionData);
            
            $transaction->save();
            
            $saveData = array(
                'oxid' => $oOrder->oxorder__oxuserid->rawValue,
                'clientid' => $this->_paymentProcessor->getClientId()
            );

            if ($oxConfig->getShopConfVar('PAYMILL_ACTIVATE_FASTCHECKOUT')) {
                $paymentColumn = 'paymentID_' . strtoupper($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue));
                $saveData[$paymentColumn] = $this->_paymentProcessor->getPaymentId();
            }

            $this->_fastCheckoutData->assign($saveData);
            $this->_fastCheckoutData->save();

            if ($oxConfig->getShopConfVar('PAYMILL_SET_PAYMENTDATE')) {
                $this->_setPaymentDate($oOrder);
            }

            // set transactionId to session for updating the description after order execute
            $transactionId = $this->_paymentProcessor->getTransactionId();
            $this->getSession()->setVariable('paymillPgTransId', $transactionId);
        } else {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($this->_getErrorMessage($this->_paymentProcessor->getErrorCode()));
        }

        return $result;
    }

    private function _existingClientHandling($oOrder)
    {
        $clientId = $this->_fastCheckoutData->paymill_fastcheckout__clientid->rawValue;
        if (!empty($clientId)) {
            $this->_clients = new Services_Paymill_Clients(
                trim(oxRegistry::getConfig()->getShopConfVar('PAYMILL_PRIVATEKEY')),
                $this->_apiUrl
            );

            $client = $this->_clients->getOne($clientId);

            if ($oOrder->oxorder__oxbillemail->value !== $client['email']) {
                $this->_clients->update(
                    array(
                        'id' => $clientId,
                        'email' => $oOrder->oxorder__oxbillemail->value
                    )
                );
            }

            if (array_key_exists('email', $client)) {
                $this->_paymentProcessor->setClientId($clientId);
            }
        }
    }

    private function _initializePaymentProcessor($dAmount, $oOrder)
    {
        $utf8Name = $this->paymillConvertToUtf(
            $oOrder->oxorder__oxbilllname->value . ', ' . $oOrder->oxorder__oxbillfname->value
        );

        $description = 'OrderID: ' . $oOrder->oxorder__oxid . ' - ' . $utf8Name;

        $description = strlen($description) > 128? substr($description,0,128) : $description;

        $this->_paymentProcessor = new Services_Paymill_PaymentProcessor(
            trim(oxRegistry::getConfig()->getShopConfVar('PAYMILL_PRIVATEKEY')),
            $this->_apiUrl,
            null,
            array(
                'token' => $this->_token,
                'amount' => (int) round($dAmount * 100),
                'currency' => strtoupper($oOrder->oxorder__oxcurrency->rawValue),
                'name' => $utf8Name,
                'email' => $oOrder->oxorder__oxbillemail->value,
                'description' => $description
            ),
            $this
        );

        $this->_paymentProcessor->setSource($this->_getSourceInfo());
    }

    private function _loadFastCheckoutData()
    {
        $this->_fastCheckoutData = oxNew('paymill_fastcheckout');
        $this->_fastCheckoutData->load($this->getUser()->getId());
    }

    private function _getPaymentShortCode($paymentCode)
    {
        $paymentType = split('_', $paymentCode);
        return $paymentType[1];
    }

    private function _setPaymentDate($oOrder)
    {
        $oDb = oxDb::getDb();
        $sDate = date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime());
        $sQ = 'update oxorder set oxpaid=\'' . $sDate . '\' where oxid=' . $oDb->quote($oOrder->getId());
        $oOrder->oxorder__oxorderdate = new oxField($sDate, oxField::T_RAW);
        $oDb->execute($sQ);
    }

    private function _getSourceInfo()
    {
        $modul = oxNew('oxModule');
        $modul->load('paymill');

        return $modul->getInfo('version') . '_oxid_' . oxRegistry::getConfig()->getVersion();
    }

    /**
     * log the given message
     *
     * @param string $message
     * @param string $debuginfo
     *
     * @todo  remove this use paymill_logger instead
     */
    public function log($message, $debuginfo)
    {
        if (oxRegistry::getConfig()->getShopConfVar('PAYMILL_ACTIVATE_LOGGING')) {
            $logging = oxNew('paymill_logging');
            $logging->assign(array(
                'identifier' => $this->getSession()->getVariable('paymill_identifier'),
                'debug' => $debuginfo,
                'message' => $message,
                'date' => date('Y-m-d H:i:s', oxRegistry::get('oxUtilsDate')->getTime())
            ));

            $logging->save();
        }
    }

    public function paymillConvertToUtf($value)
    {
       $obj = oxNew('paymill_util');
       return $obj->convertToUtf($value);
    }

}
