import Words.java

public class Scores {
	
	private int total = 0;
	private int right = 0;
	private int wrong = 0;

	Scores () {

	}

	Scores (int total) { 

		this.total = total;

	}

	public void setRight () {

		right = right + 1;

	}

	public int getRight () {

		return right;

	}
	
	public void setWrong () {

		wrong = wrong + 1;

	}

	public int getWrong () {

		return wrong;

	}

	public void setTotal (int total) {

		this.total = total;

	}

	public int getTotal () {

		return total;

	}

	public double percentWrong () {

		return (wrong / total);

	}

	public double percentRight () {

		return (right / total);

	}

}
