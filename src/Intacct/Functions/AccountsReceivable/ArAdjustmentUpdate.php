<?php
namespace Intacct\Functions\AccountsReceivable;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

/**
 * Create a new accounts receivable adjustment record
 */
class ArAdjustmentUpdate extends AbstractArAdjustment {

	public function writeXml(XMLWriter &$xml)
	{
		$xml->startElement('function');
		$xml->writeAttribute('controlid', $this->getControlId());

		$xml->startElement('update');
		$xml->startElement('aradjustment');

		$xml->writeElement('RECORDNO', $this->getRecordNo());

		$xml->writeElement('customerid', $this->getCustomerId(), true);

		$xml->writeElement('WHENCREATED', $this->getTransactionDate()->format('Y-m-d'));

		$xml->writeElement('action', $this->getAction());
		$xml->writeElement('batchkey', $this->getSummaryRecordNo());
		$xml->writeElement('RECORDID', $this->getAdjustmentNumber());
		$xml->writeElement('DOCNUMBER', $this->getInvoiceNumber());
		$xml->writeElement('description', $this->getDescription());
		$xml->writeElement('externalid', $this->getExternalId());


		$this->writeXmlMultiCurrencySection($xml);

		$xml->writeElement('nogl', $this->isDoNotPostToGL());

		$xml->startElement('aradjustmentitems');
		if (count($this->getLines()) > 0) {
			foreach ($this->getLines() as $line) {
				$line->writeXml($xml);
			}
		} else {
			throw new InvalidArgumentException('AR Invoice must have at least 1 line');
		}
		$xml->endElement(); //arajustmentitems

		$xml->endElement(); //arajustmentupdate

		$xml->endElement(); //update

		$xml->endElement(); //function
	}
}