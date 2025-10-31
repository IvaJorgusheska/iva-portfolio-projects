/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

import java.awt.Color;
import java.awt.Dimension;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JFrame;
import javax.swing.Timer;

public class OthelloDisplay extends JFrame implements MouseListener,ActionListener {
    // Basic dimensions for board and canvas geometry
    private static final int SQUARESIZE= 60;
    private static final int PIECERADIUS= (int) (0.4*SQUARESIZE);
    private static final int MARGIN= 50; // unit for padding around board
    private static final boolean DEBUG= true; // show player move statistics on terminal

    // Position of board on canvas
    private static final int xBoardStart= MARGIN;
    private static final int yBoardStart= 2*MARGIN;
    private static final int xBoardEnd= xBoardStart + 8*SQUARESIZE;
    private static final int yBoardEnd= yBoardStart + 8*SQUARESIZE;

    // Basic properties of window canvas
    private static final int xCanvasSize= 8*SQUARESIZE + 2*MARGIN;
    private static final int yCanvasSize= 8*SQUARESIZE + 3*MARGIN;
    private static final String titleString= "emaG isreveR xaminiM s'HP naI";
    private static final String copyLine= "\u00a9 Ian, Francisco 2023";
    private static final Color mainColour= Color.green;

    // Layout properties of window canvas
    private static int xLeftInfo= xBoardStart - MARGIN/2; // left and right aligned text
    private static int xRightInfo= xBoardEnd + MARGIN/2;
    private static int yTopInfo, yBottomInfo; // baselines for text above and below board
    private FontMetrics fm;
    private String resultLine= ""; // results appear only when game is over

    // Game objects
    private BoardState boardState;
    private MoveChooser whiteChooser;
    private MoveChooser blackChooser;
    private Timer playerTimer;

    private static final int timerDelay= 2000; // milliseconds
    private static final int sleepDelay= 100; // milliseconds

    // Main draw window method
    public void paint(Graphics g) {
        super.paint(g);
        drawBoard(g);
        drawInfo(g);
    }

    // Draw board on canvas
    private void drawBoard(Graphics g) {
        // work variables for coordinates
        int x, y, d;
        // Draw the grid
        x= xBoardStart;
        y= yBoardStart;
        d= 2*PIECERADIUS;
        for (int i= 0; i < 9; i++) {
            g.drawLine(x, yBoardStart, x, yBoardEnd-1);
            x+= SQUARESIZE;
            g.drawLine(xBoardStart, y, xBoardEnd-1, y);
            y+= SQUARESIZE;
        }
        // Draw the pieces
        x= xBoardStart + SQUARESIZE/2;
        for (int i= 0; i < 8; i++) { // column-by-column
            y= yBoardStart + SQUARESIZE/2;
            for (int j= 0; j < 8; j++){
                if (boardState.getContents(i,j) == 1) {
                    g.setColor(Color.WHITE);
                    g.fillOval(x-PIECERADIUS, y-PIECERADIUS, d, d);
                } else if (boardState.getContents(i,j) == -1) {
                    g.setColor(Color.BLACK);
                    g.fillOval(x-PIECERADIUS, y-PIECERADIUS, d, d);
                }
                y+= SQUARESIZE;
            }
            x+= SQUARESIZE;
        }
    }

    // Draw text info on canvas
    private void drawInfo(Graphics g) {
        if (fm == null)
            return;
        String msg;
        g.setColor(Color.BLACK);
        // First info line above board
        msg= "White: " + whiteChooser.toString();
        g.drawString(msg, xLeftInfo, yTopInfo-fm.getHeight());
        msg= "Board Score: " + boardState.getScore();
        g.drawString(msg, xRightInfo-fm.stringWidth(msg), yTopInfo-fm.getHeight());
        // Second info line above board
        msg= "Black: " + blackChooser.toString();
        g.drawString(msg, xLeftInfo, yTopInfo);
        msg= resultLine;
        g.drawString(msg, xRightInfo-fm.stringWidth(msg), yTopInfo);
        // Single info line below board
        msg= statusLine();
        g.drawString(msg, xLeftInfo, yBottomInfo);
        msg= copyLine;
        g.drawString(msg, xRightInfo-fm.stringWidth(msg), yBottomInfo);
    }
    // Status line changes to indicate who plays and how play is done
    private String statusLine() {
        if (!resultLine.isEmpty())
            return "Game over!";
        // Build instructions for next move
        String colour;
        boolean human;
        if (boardState.colour == 1) {
            colour= "White";
            human= whiteChooser.isHuman();
        } else {
            colour= "Black";
            human= blackChooser.isHuman();
        }
        if (human)
            return "Click outside grid if " + colour + " has no moves";
        else
            return "Wait 2s or click for " + colour + " to move";
    }

    // Initialise game UI
    public OthelloDisplay(BoardState board, MoveChooser white, MoveChooser black) {
        super(titleString);
        // record game objects
        System.out.println("Loading game...");
        boardState= board;
        whiteChooser= white;
        blackChooser= black;
        // coordinates to center window on screen
        Dimension screenSize= Toolkit.getDefaultToolkit().getScreenSize();
        int xCanvasPos= (screenSize.width - xCanvasSize)/2;
        int yCanvasPos= (screenSize.height - yCanvasSize)/2;
        // set up main window
        setSize(xCanvasSize,yCanvasSize);
        setLocation(xCanvasPos,yCanvasPos);
        getContentPane().setBackground(mainColour);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setVisible(true);
        // initialise text layout properties
        fm= getGraphics().getFontMetrics();
        yTopInfo= yBoardStart - (MARGIN - fm.getHeight())/2 - fm.getDescent() - fm.getLeading();
        yBottomInfo= yBoardEnd + (MARGIN - fm.getHeight())/2 + fm.getAscent();
        // set up handler for window closing
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent e) {
                Toolkit.getDefaultToolkit().beep();
                System.out.println("Current boardState: " + boardState);
                System.out.println("Current boardScore: " + boardState.getScore());
                if (!resultLine.isEmpty())
                    System.out.println("Current resultLine: " + resultLine);
                System.exit(0);
            }
        });
        // set up human game controls human via mouse
        addMouseListener(this);
    }

    // start up game once font metrics are available
    public void startGame() {
        repaint();
        // wait for FontMetrics :(
        while (fm == null) {
            try {
                Thread.sleep(sleepDelay);
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }
        }
        // set up robot game control via timer
        setPlayerTimer();
    }

    // get current player
    private MoveChooser getCurrentPlayer() {
        if (boardState.colour == 1) { // White to move
            return whiteChooser;
        } else { // Black to move
            return blackChooser;
        }
    }

    // Move according to human hint or after robot timer
    private void makePlayerMove(Move hintMove) {
        MoveChooser player= getCurrentPlayer();
        Move playerMove;
        if (DEBUG) {
            MoveStats moveStats= new MoveStats(player,boardState,hintMove);
            System.out.println(player.toString() + ": " + moveStats.toString());
            playerMove= moveStats.move;
        } else
            playerMove= player.chooseMove(boardState,hintMove);
        if (boardState.checkLegalMove(playerMove)) {
            // Recognizable move made and it is legal
            boardState.makeLegalMove(playerMove);
            if (boardState.gameOver())
                resultLine= boardState.resultString();
            else
                setPlayerTimer();
            paint(getGraphics());
        } else
            // Illegal move
            Toolkit.getDefaultToolkit().beep();
    }

    // Implement ActionListener interface
    @Override
    public void actionPerformed(ActionEvent e) {
        makePlayerMove(new Move());
    }

    private void setPlayerTimer() {
        // Do nothing if current player is human
        if (getCurrentPlayer().isHuman())
            return;
        // Initilise timer that triggers actionPerformed()
        if (playerTimer == null) {
            playerTimer= new Timer(timerDelay,this);
            playerTimer.setRepeats(false);
            playerTimer.start();
        } else
            playerTimer.restart();
    }

    // Implement MouseListener interface
    @Override
    public void mouseClicked(MouseEvent e){
        Move hintMove= getPlayerHint(e);
        makePlayerMove(hintMove);
    }
    @Override
    public void mousePressed(MouseEvent e) {
    }
    @Override
    public void mouseReleased(MouseEvent e) {
    }
    @Override
    public void mouseEntered(MouseEvent e) {
    }
    @Override
    public void mouseExited(MouseEvent e) {
    }

    private static Move getPlayerHint(MouseEvent e) {
        // Assemble the action to be performed
        int x= e.getX();
        int y= e.getY();
        // hintMove is null until user clicks in something vaguely recognizable
        Move hintMove= null;
        if (isInSkipArea(x,y))
            // Make skip move
            hintMove= new Move();
        else {
            int i= xCanvasToSquare(x);
            int j= yCanvasToSquare(y);
            // Exception if (i,j) coordinates fall outside board
            hintMove= new Move(i,j);
        }
        return hintMove;
    }
    // Convert canvas position to board coordinates
    private static int xCanvasToSquare(int x) {
        if ((x < xBoardStart) || (x >= xBoardEnd))
            return -1;
        return (x-xBoardStart) / SQUARESIZE;
    }
    private static int yCanvasToSquare(int y) {
        if ((y < yBoardStart) || (y >= yBoardEnd))
            return -1;
        return (y-yBoardStart) / SQUARESIZE;
    }
    // We may add a button later. For the moment,
    // we take the skip area to be anywhere outside the board proper.
    private static boolean isInSkipArea(int x, int y) {
        return (xCanvasToSquare(x) < 0) || (yCanvasToSquare(y) < 0);
    }
}

/* vim:set et ts=4 sw=4: */
