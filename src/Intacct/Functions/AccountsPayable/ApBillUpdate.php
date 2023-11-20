<?php

namespace Intacct\Functions\AccountsPayable;

use Intacct\Xml\XMLWriter;

class ApBillUpdate extends AbstractBill {

	public function writeXml(XMLWriter &$xml) {
		$xml->startElement('function');
		$xml->writeAttribute('controlid', $this->getControlId());

		$xml->startElement('update');
		$xml->startElement('apbill');

		$xml->writeElement('RECORDNO', $this->getRecordNo());

		$xml->writeElement('vendorid', $this->getVendorId(), true);

		$xml->startElement('datecreated');
		$xml->writeDateSplitElements($this->getTransactionDate());
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
		$xml->writeElement('billno', $this->getBillNumber());
		$xml->writeElement('ponumber', $this->getReferenceNumber());
		$xml->writeElement('onhold', $this->isOnHold());
		$xml->writeElement('description', $this->getDescription());
		$xml->writeElement('externalid', $this->getExternalId());

		if ($this->getPayToContactName()) {
			$xml->startElement('payto');
			$xml->writeElement('contactname', $this->getPayToContactName());
			$xml->endElement(); //payto
		}

		if ($this->getReturnToContactName()) {
			$xml->startElement('returnto');
			$xml->writeElement('contactname', $this->getReturnToContactName());
			$xml->endElement(); //returnto
		}

		$this->writeXmlMultiCurrencySection($xml);

		$xml->writeElement('nogl', $this->isDoNotPostToGL());
		$xml->writeElement('supdocid', $this->getAttachmentsId());
		$xml->writeElement('taxsolutionid', $this->getTaxSolutionId());

		$this->writeXmlExplicitCustomFields($xml);

		$xml->startElement('apbillitems');
		if (count($this->getLines()) > 0) {
			foreach ($this->getLines() as $line) {
				$line->writeXml($xml);
			}
		} else {
			throw new InvalidArgumentException('AP Bill must have at least 1 line');
		}
		$xml->endElement(); //billitems

		$xml->endElement(); //apbill

		$xml->endElement(); //update

		$xml->endElement(); //function
	}
}
