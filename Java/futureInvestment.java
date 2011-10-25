import javax.swing.JOptionPane;

public class futureInvestment
{

  public static double futureInvestmentValue(double investmentAmount,
                        double monthlyInterestRate, int years)
  {

    int numberOfYears = years * 12;
    double interestRate = 1.0 + monthlyInterestRate;
    double result = Math.pow(interestRate, numberOfYears);
    return (investmentAmount * result);

  }

  public static void main (String[] args)
  {

    Double investmentAmount = Double.parseDouble(JOptionPane.showInputDialog("Enter Investment Amount:"));

    Double monthlyInterestRate = Double.parseDouble(JOptionPane.showInputDialog("Enter Montly Interest Rate:"));

    String years = JOptionPane.showInputDialog(null, "Enter Investment Years:", "Investment Years", JOptionPane.QUESTION_MESSAGE);

    System.out.println(futureInvestmentValue(investmentAmount, monthlyInterestRate, Integer.valueOf(years).intValue()));

  }

}
