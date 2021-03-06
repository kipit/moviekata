<?php

namespace MovieRental;

use MovieRental\Rental;

class Customer {
	private $name;
	private $rentals = array();

	public function __construct($name){
		$this->name = $name;
	}

	public function addRental(Rental $rental){
		array_push($this->rentals, $rental);
	}

	public function getName(){
		return $this->name;
	}

	public function statement(){
		$totalAmount = 0;
		$frequentRenterPoints = 0;
		$result = "Rental Record for " . $this->getName() . "\n";
		
		// determine amounts for each line
		foreach($this->rentals as $rental) {
			$thisAmount = 0;
			
			switch($rental->getMovie()->getPriceCode()){
				case Movie::REGULAR:
					$thisAmount += 2;
					if($rental->getDaysRented() > 2)
						$thisAmount += ($rental->getDaysRented() - 2) * 1.5;
					break;
				case Movie::NEW_RELEASE:
					$thisAmount += $rental->getDaysRented() * 3;
					break;
				case Movie::CHILDRENS:
					$thisAmount += 1.5;
					if($rental->getDaysRented() > 3)
						$thisAmount += ($rental->getDaysRented() - 3) *1.5;
					break;
			}
			
			// frequent renter points
			$frequentRenterPoints++;
			
			// add bonus for a two day new release rental
			if(($rental->getMovie()->getPriceCode() == Movie::NEW_RELEASE) && 
					$rental->getDaysRented() > 1) $frequentRenterPoints++;
			
			// show figures for this rental
			$result .= "\t" . $rental->getMovie()->getTitle() . "\t" .
						$thisAmount . "\n";
			$totalAmount += $thisAmount;
		}
		
		// print footer lines
		$result .= "Amount owed is " . $totalAmount . "\n";
		$result .= "You earned " . $frequentRenterPoints .
				" frequent renter points";
		
		return $result;
	}
}