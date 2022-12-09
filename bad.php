<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model{
    public $accountingService;
    public int $id;

    function __construct(){
        $this->accountingService = new \App\Services\AccountingService();
    }

    public function get_invoices()
    {
        $tenantIds = array('tenant_id' => $this->id);
        $invoices = $this->accountingService->getAllInvoices($tenantIds);
        $ap_invoices = array();
        if (!empty($invoices))
        {
            // Loop through all invoices and choose only ones that await payment
            foreach ($invoices as $invoice)
            {
                if ($invoice['status'] == 'awaiting-payment')
                    $ap_invoices[] = $invoice;
            }
            return $ap_invoices;
        }
        return null;
    }

    public function get_old_invoices()
    {
        $tenantIds = array('tenant_id' => $this->id);
        $invoices = $this->accountingService->getAllInvoices($tenantIds);

        if (!empty($invoices)) {
            $paid_invoices = array();

            // Loop through all invoices and choose only paid ones
            foreach ($invoices as $invoice)
            {
                if ($invoice['status'] == 'paid') {
                    $paid_invoices[] = $invoice;
                }
                return $paid_invoices;
            }
            return null;
        }
    }
}
