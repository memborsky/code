// MessagePanel.java: Display a message on a JPanel
   import java.awt.Font;
   import java.awt.FontMetrics;
   import java.awt.Dimension;
   import java.awt.Graphics;
   import javax.swing.JPanel;

    public class MessagePanel extends JPanel {
   	/** The message to be displayed */
      private String message = "Welcome to Java.";
   
   	/** The x coordinate where the message is displayed */
      private int xCoordinate = 20;
   
   	/** The y coordinate where the message is displayed */
      private int yCoordinate = 20;
   
   	/** Indicate weather the message is displayed in the center */
      private boolean centered;
   
   	/** Default constructor */
       public MessagePanel () {
      }
   
   	/** Constructor with a message parameter */
       public MessagePanel (String message) {
         this.message = message;
      }
   
   	/** Return message */
       public String getMessage () {
         return message;
      }
   
   	/** Set a new message */
       public void setMessage (String message) {
         this.message = message;
      }
   
   	/** Return xCoordinate */
       public int getXCoordinate () {
         return xCoordinate;
      }
   
   	/** Set a new xCoordinate */
       public void setXCoordiante (int x) {
         this.xCoordinate = x;
      }
   
   	/** Return yCoordinate */
       public int getYCoordinate () {
         return yCoordinate;
      }
   
   	/** Set a new yCoordinate */
       public void setYCoordinate (int y) {
         this.yCoordinate = y;
      }
   
   	/** Return Centered */
       public boolean isCentered () {
         return centered;
      }
   
   	/** Set a new centered */
       public void setCentered (boolean centered) {
         this.centered = centered;
      }
   
   	/** Paint the message */
       public void paintComponent (Graphics g) {
         super.paintComponent(g);
      
         if (centered) {
         	// Get font metrics for the current font
            FontMetrics fm = g.getFontMetrics();
         
         	// Find the center location to display
            int w = fm.stringWidth(message); // Get the string width
         	// Get the string height, from top to the baseline
            int h = fm.getAscent();
            xCoordinate = getWidth() / 2 - w / 2;
            yCoordinate = getHeight() / 2 + h / 2;
         }
      
         g.drawString(message, xCoordinate, yCoordinate);
      }
   
   	/** Override get method for preferredSize */
       public Dimension getPreferredSize () {
         return new Dimension(200, 100);
      }
   
   	/** Override get method for minimumSize */
       public Dimension getMinimumSize () {
         return new Dimension (200, 100);
      }
   }
