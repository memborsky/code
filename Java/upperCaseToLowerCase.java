import javax.swing.JOptionPane;

public class upperCaseToLowerCase
{

	public static char upperToLower(char ch)
	{
		return (char)((int)ch + 32);
//		return Character.toLowerCase(ch);
	}

	public static void main (String args[])
	{
		String input = JOptionPane.showInputDialog(null, "Enter String", "Input", JOptionPane.QUESTION_MESSAGE);

		char upperCase = input.charAt(0);
		
		System.out.println(upperToLower(upperCase) + input.substring(1));
	}

}