// Mortgage.java: Encapsulate mortgage information
public class Mortgage {
	private double annualInterestRate;
	private int numOfYears;
	private double loanAmount;

	public Mortgage() {
		this(7.5, 30, 100000);
	}

	public Mortgage (double annualInterestRate, int numOfYears, double loanAmount) {
		this.annualInterestRate = annualInterestRate;
		this.numOfYears = numOfYears;
		this.loanAmount = loanAmount;
	}

	public double getAnnualInterestRate () {
		return annualInterestRate;
	}

	public void setAnnualInterestRate (double annualInterestRate) {
		this.annualInterestRate = annualInterestRate;
	}

	public int getNumOfYears () {
		return numOfYears;
	}

	public void setNumOfYears (int numOfYears) {
		this.numOfYears = numOfYears;
	}

	public double getLoanAmount () {
		return loanAmount;
	}

	public void setLoanAmount () {
		this.loanAmount = loanAmount;
	}

	public double montlyPayent() {
		double montlyInterestRate = annualInterestRate / 1200;
		return loanAmount + montlyInterestRate / (1 - (Math.pow(1 / (1 + montlyInterestRate), numOfYears * 12)));
	}

	public double totalPayment () {
		return montlyPayment() * numOfYears * 12;
	}
}
