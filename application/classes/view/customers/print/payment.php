<?php defined('SYSPATH') or die('No direct access allowed.');
/*
BeansBooks
Copyright (C) System76, Inc.

This file is part of BeansBooks.

BeansBooks is free software; you can redistribute it and/or modify
it under the terms of the BeansBooks Public License.

BeansBooks is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the BeansBooks Public License for more details.

You should have received a copy of the BeansBooks Public License
along with BeansBooks; if not, email info@beansbooks.com.
*/


class View_Customers_Print_Payment extends View_Print {

	public function deposit_account()
	{
		if( ! isset($this->payment->deposit_transaction) OR 
			! $this->payment->deposit_transaction )
			return FALSE;

		return array(
			'name' => $this->payment->deposit_transaction->account->name,
		);
	}

	public function writeoff_account()
	{
		if( ! isset($this->payment->writeoff_transaction) OR 
			! $this->payment->writeoff_transaction )
			return FALSE;

		return array(
			'name' => $this->payment->writeoff_transaction->account->name,
		);
	}

	public function payment_number()
	{
		return $this->payment->number;
	}

	public function payment_date_formatted()
	{
		return date("F j, Y",strtotime($this->payment->date));
	}
	
	public function writeoff_total_formatted()
	{
		$beans_settings = parent::beans_settings();

		if( ! isset($this->payment->writeoff_transaction) OR 
			! $this->payment->writeoff_transaction )
			return $beans_settings->company_currency.number_format(0,2,'.',',');

		return ( $this->payment->writeoff_transaction->amount < 0 ? '-' : '' ).$beans_settings->company_currency.number_format(abs($this->payment->writeoff_transaction->amount),2,'.',',');
	}

	public function check_number()
	{
		return $this->payment->check_number;
	}

	public function total_formatted()
	{
		$beans_settings = parent::beans_settings();

		return ( $this->payment->amount < 0 ? '-' : '' ).$beans_settings->company_currency.number_format(abs($this->payment->amount),2,'.',',');
	}

	protected $_payment_lines = FALSE;
	public function payment_lines()
	{
		if( $this->_payment_lines )
			return $this->_payment_lines;

		$beans_settings = parent::beans_settings();

		$this->_payment_lines = array();

		$i = 0;
		foreach( $this->payment->sale_payments as $sale_payment )
			$this->_payment_lines[] = array(
				'odd' => ( $i++ % 2 == 0 ? TRUE : FALSE ),
				'customer_name' => $sale_payment->sale->customer->first_name.' '.$sale_payment->sale->customer->last_name,
				'sale_number' => $sale_payment->sale->sale_number,
				'quote_number' => $sale_payment->sale->quote_number,
				'order_number' => $sale_payment->sale->order_number,
				'po_number' => $sale_payment->sale->po_number,
				'date_due' => $sale_payment->sale->date_due,
				'amount_formatted' => ( $sale_payment->amount < 0 ? '-' : '' ).$beans_settings->company_currency.number_format(abs($sale_payment->amount),2,'.',','),
			);

		return $this->_payment_lines;
	}

}