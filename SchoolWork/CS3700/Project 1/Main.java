import javax.swing.JOptionPane, Words.java, Scores.java;

public class Main {

	private int total = 0;
	private String name;
	private String word;
	private String definition;

	Main() {
		
		String output = JOptionPane.showMessageDialog("Error : No User Submitted", "Please input a valid Name",
				"Example 6.9 Output", JOptionPane.INFORMATION_MESSAGE);
				
	}

	Main (String person) {

		Words Word = new Words();
		Scores score = new Scores();
		name = person;
		total = getTotal();
		int index = 1;
		while (index <= total) {

			Word.generateNewQuestion();
			word = Word.getWord();
			definition = Word.getDefinition();
			String output = JOptionPane.showMessageDialog(null, definition, "Example 6.9 Output",
					JOptionPane.INFORMATION_MESSAGE);
			output += "\n\n";
			output += JOptionPane.showMessageDialog(null, "Enter an answer:", "Example 6.9 Input",
					JOptionPane.QUESTION_MESSAGE);
			String input = JOptionPane.showMessageDialog(null, output, "Example 6.9 Input",
					JOptionPane.INFORMATION_MESSAGE);
			String answer = String.parseString(intput);

		}

	}
		

	public int getTotal () {

		String totalString = JOptionPane.showInformationDialog(null, "How many questions:", "Example 6.9 Input",
				JOptionPane.QUESTION_MESSAGE);
		int total = Integer.parseInt(totalString);
		return total;
		
	}

	private boolean askQuestion () {



}
