// TestFontMetrics.java: Draw a message at the cetner of a panel
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.event.*;
import javax.swing.*;

public class TestFontMetrics extends JFrame {

	private JButton jbtRefresh = new JButton ("Refresh")
	
	public TestFontMetrics () {
		MessagePanel messagePanel = new MessagePanel ("");
      
		messagePanel.setFont(new Font("SansSerif", Font.BOLD, 20));
      
		messagePanel.setCentered(true);
      
		getContentPane().add(messagePanel);
		getContentPane().add(jbtRefresh);
				
	        //ButtonListener btListener = new ButtonLIstener();

		//jbtRefresh.addActionListener(btListener);
	}
   
	public static void main (String[] args) {
        	TestFontMetrics frame = new TestFontMetrics();
		
	        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        	frame.setSize(300, 200);
	        frame.setTitle("w00t w00t");
        	frame.setVisible(true);
	}

	public void actionPerformed(ActionEvent e) {
		if (e.getActionCommand().equals("Refresh")) {
			getContentPane().remove(message);
			message = arr.getNewMessage();
			getContentPane().add(message);
		}
	}
}
