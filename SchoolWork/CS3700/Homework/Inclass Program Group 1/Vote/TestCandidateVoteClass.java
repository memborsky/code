import javax.swing.JOptionPane;

public class TestCandidateVoteClass {
	public static void main ( String [] args ) {
		Candidate candidate1 = new Candidate("George King");
		Candidate candidate2 = new Candidate("Kim Jones");

		while (true) {
			String voteString = JOptionPane.showInformationDialog(null,
					"Enter a vote:",
					"Example 6.9 Input", JOptionPane.QUESTION_MESSAGE);

			int vote = Integer.parseInt(voteString);
			if (vote == 0) break;
			else if (vote == 1) candidate1.getVote().increment();
			else if (vote == 2) candidate2.getVote().increment();
			else if (vote == -1) candidate1.getVote().decrement();
			else if (vote == -2) candidate2.getVote().decrement();
		}

		String output = "The total number of candidates is " + Candidate.getNumOfCandidates();
		output += "\nThe votes for " + candidate1.getName() + " is " + candidate1.getVote().getCount();
		output += "\nThe votes for " + candidate2.getName() + " is " + candidate2.getVote().getCount();

		JOptionPane.showMessageDialog(null, output, "Example 6.9 Output", JOptionPane.INFORMATION_MESSAGE);

		System.exit(0);
	}
}

class Vote {
	private int count = 0;
	
	public int getCount() {
		return count;
	}

	public void setCount(int count) {
		this.count = count;
	}

	public void clear () {
		count = 0;
	}

	public void increment() {
		count++;
	}

	public void decrement() {
		count--;
	}
}

class Candidate {
	private String name;
	private Vote vote;
	private static int numOfCandidates = 0;

	public Candidate(String name) {
		this.name = name;
		vote = new Vote();
		numOfCandidates++;
	}

	public Vote getVote() {
		return vote;
	}

	public String getName() {
		return name;
	}

	public static int getNumOfCandidates() {
		return numOfCandidates;
	}
}
