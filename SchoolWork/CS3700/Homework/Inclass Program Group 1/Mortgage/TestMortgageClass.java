import javax.swing.JOptionPane;

public class TestMortgageClass {
	public static void main ( String [] args ) {
		String annualInterestRateString = JOptionPane.showInputDialog(null,
				"Enter Yearly interest rate, for example 8.25",
				"Example 6.8 Input", JOptionPane.QUESTION_MESSAGE);

		double annualInterestRate = Double.parseDouble(annualInterestRateString);

		String numOfYearsString = JOption.showInputDialog(null,
				"Enter number of years as an integer, \nfor example 5:",
				"Example 6.8 Input", JOptionPane.QUESTION_MESSAGE);

		int numOfYears = Integer.parseInt(numOfYearsString);

		String loanString = JOption.showInputDialog(null,
				"Enter loan amount, for example 120000.95:",
				"Example 6.8 Input", JOptionPane.QUESTION_MESSAGE);

		double loanAmount = Double.parseDouble(loanString);

		Mortgage mortgage = new Mortgage(annualInterestRate, numOfYears, loanAmount);

		double montlyPayment = (int) (mortgage.monthlyPayment() * 100) / 100.0;
		double totalPayment = (int) (mortgage.totalPayment() * 100) / 100.0;

		String output = "The monthly payment is " + monthlyPayment + "\nThe total payment is " + totalPayment;
		JOPtionPane.showMessageDialog(null, output, "Example 6.8 Output", JOptionPane.INFORMATION_MESSAGE);

		System.exit(0);
	}
}
