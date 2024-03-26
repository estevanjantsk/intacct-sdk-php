<?php


namespace Intacct\Functions\AccountsReceivable;

use Intacct\Xml\XMLWriter;

class InvoiceLineUpdate extends AbstractInvoiceLine {

	public function writeXml(XMLWriter &$xml) {
		$xml->startElement('arinvoiceitem');

		if (!empty($this->getAccountLabel())) {
			$xml->writeElement('accountlabel', $this->getAccountLabel(), true);
		} else {
			$xml->writeElement('glaccountno', $this->getGlAccountNumber(), true);
		}

		$xml->writeElement('offsetglaccountno', $this->getOffsetGLAccountNumber());
		$xml->writeElement('amount', $this->getTransactionAmount(), true);
		$xml->writeElement('allocationid', $this->getAllocationId());
		$xml->writeElement('entrydescription', $this->getMemo());
		$xml->writeElement('locationid', $this->getLocationId());
		$xml->writeElement('departmentid', $this->getDepartmentId());
		$xml->writeElement('key', $this->getKey());
		$xml->writeElement('totalpaid', $this->getTotalPaid());
		$xml->writeElement('totaldue', $this->getTotalDue());

		$this->writeXmlExplicitCustomFields($xml);

		$xml->writeElement('revrectemplate', $this->getRevRecTemplateId());
		$xml->writeElement('defrevaccount', $this->getDeferredRevGlAccountNo());
		if ($this->getRevRecStartDate()) {
			$xml->startElement('revrecstartdate');
			$xml->writeDateSplitElements($this->getRevRecStartDate(), true);
			$xml->endElement();
		}
		if ($this->getRevRecEndDate()) {
			$xml->startElement('revrecenddate');
			$xml->writeDateSplitElements($this->getRevRecEndDate(), true);
			$xml->endElement();
		}

		$xml->writeElement('projectid', $this->getProjectId());
		$xml->writeElement('customerid', $this->getCustomerId());
		$xml->writeElement('vendorid', $this->getVendorId());
		$xml->writeElement('employeeid', $this->getEmployeeId());
		$xml->writeElement('itemid', $this->getItemId());
		$xml->writeElement('classid', $this->getClassId());
		$xml->writeElement('contractid', $this->getContractId());
		$xml->writeElement('warehouseid', $this->getWarehouseId());

		// if there are tax entries, lets add them to our xml
		if (!empty($this->getTaxEntry())) {
			$xml->startElement('taxentries');
			foreach ($this->getTaxEntry() as $taxentry) {
				$taxentry->writeXml($xml);
			}
			$xml->endElement(); //taxentries
		}

		$xml->endElement(); //lineitem
	}
}
