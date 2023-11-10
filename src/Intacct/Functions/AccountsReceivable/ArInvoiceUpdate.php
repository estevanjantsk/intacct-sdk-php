<?php

namespace Intacct\Functions\AccountsReceivable;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class ArInvoiceUpdate extends AbstractInvoice {

	public function writeXml(XMLWriter &$xml)
	{
		$xml->startElement('function');
		$xml->writeAttribute('controlid', $this->getControlId());

		$xml->startElement('update');
		$xml->startElement('arinvoice');

		$xml->writeElement('RECORDNO', $this->getRecordNo());

		$xml->writeElement('customerid', $this->getCustomerId(), true);

		$xml->startElement('datecreated');
		$xml->writeDateSplitElements($this->getTransactionDate(), true);
		$xml->endElement(); //datecreated

		if ($this->getGlPostingDate()) {
			$xml->startElement('dateposted');
			$xml->writeDateSplitElements($this->getGlPostingDate(), true);
			$xml->endElement(); //dateposted
		}

		if ($this->getDueDate()) {
			$xml->startElement('datedue');
			$xml->writeDateSplitElements($this->getDueDate(), true);
			$xml->endElement(); // datedue

			$xml->writeElement('termname', $this->getPaymentTerm());
		} else {
			$xml->writeElement('termname', $this->getPaymentTerm(), true);
		}

		$xml->writeElement('action', $this->getAction());
		$xml->writeElement('batchkey', $this->getSummaryRecordNo());
		$xml->writeElement('invoiceno', $this->getInvoiceNumber());
		$xml->writeElement('ponumber', $this->getReferenceNumber());
		$xml->writeElement('onhold', $this->isOnHold());
		$xml->writeElement('description', $this->getDescription());
		$xml->writeElement('externalid', $this->getExternalId());

		if ($this->getBillToContactName()) {
			$xml->startElement('billto');
			$xml->writeElement('contactname', $this->getBillToContactName(), true);
			$xml->endElement(); //billto
		}

		if ($this->getShipToContactName()) {
			$xml->startElement('shipto');
			$xml->writeElement('contactname', $this->getShipToContactName(), true);
			$xml->endElement(); //shipto
		}

		$this->writeXmlMultiCurrencySection($xml);

		$xml->writeElement('nogl', $this->isDoNotPostToGL());
		$xml->writeElement('supdocid', $this->getAttachmentsId());
		$xml->writeElement('taxsolutionid', $this->getTaxSolutionId());

		$this->writeXmlExplicitCustomFields($xml);

		$xml->startElement('arinvoiceitems');
		if (count($this->getLines()) > 0) {
			foreach ($this->getLines() as $line) {
				$line->writeXml($xml);
			}
		} else {
			throw new InvalidArgumentException('AR Invoice must have at least 1 line');
		}
		$xml->endElement(); //invoiceitems

		$xml->endElement(); //arinvoiceupdate

		$xml->endElement(); //update

		$xml->endElement(); //function
	}
}
