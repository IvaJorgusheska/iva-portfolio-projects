/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

import java.util.Arrays;
import java.util.ArrayList;

public class BoardState {
    public int colour;
    private int[][] board;

    // https://stackoverflow.com/questions/6718749/
    @Override
    public int hashCode() {
        int hash= Arrays.deepHashCode(board) << 1;
        if (colour > 0)
            hash|= 0x1;
        return hash;
    }
    // https://stackoverflow.com/questions/27581/
    @Override
    public boolean equals(Object obj) {
        if (obj instanceof BoardState) {
            BoardState alt= (BoardState) obj;
            return Arrays.deepEquals(board,alt.board) && (colour == alt.colour);
        }
        return false;
    }

    // Get board piece in column i and row j
    public int getContents(int i, int j) {
        return board[i][j];
    }
    // Set board piece in column i and row j
    public void setContents(int i, int j, int piece) {
        if (piece > 0)
          board[i][j]=  1; // white
        else if (piece < 0)
          board[i][j]= -1; // black
        else
          board[i][j]=  0; // empty
    }

    // Calculate piece totals: white count then black count
    public int[] getTotals() {
        int white= 0;
        int black= 0;
        for (int i= 0; i < 8; i++)
            for (int j=0; j < 8; j++) {
                int piece= getContents(i,j);
                if (piece == 1)
                    white++;
                else if (piece == -1)
                    black++;
            }
        return new int[] {white, black};
    }
    // Calculate board score: >0 if white is ahead, <0 if black, and 0 if they tie
    public int getScore() {
        int[] totals= getTotals();
        return totals[0] - totals[1];
    }

    // Make string representation of board for debug purposes, etc
    @Override
    public String toString() {
        StringBuilder rep= new StringBuilder(73); // 8*8 squares + 7 pipes + 2 brackets
        if (colour > 0)
            rep.append('{');
        else
            rep.append('[');
        for (int j= 0; j < 8; j++) { // row-by-row
            if (j > 0)
                rep.append('|');
            for (int i= 0; i < 8; i++) {
                int piece= getContents(i,j);
                if (piece > 0)
                    rep.append('w'); // white
                else if (piece < 0)
                    rep.append('b'); // black
                else
                    rep.append('.'); // empty
            }
        }
        if (colour > 0)
            rep.append('}');
        else
            rep.append(']');
        return rep.toString();
    }

    // Create empty board
    public BoardState() {
        board= new int[8][8];
    }
    // Create board from string representation
    public BoardState(String str) {
        board= new int[8][8];
        String rep= (str == null)? "" : str.replaceAll("\\s","");
        if (rep.isEmpty()) {
            setContents(3, 3,  1); // Initial state
            setContents(3, 4, -1);
            setContents(4, 3, -1);
            setContents(4, 4,  1);
            colour= -1; // Black to start
        } else {
            if (rep.charAt(0) == '{')
                colour=  1; // White to start
            else
                colour= -1;
            rep= rep.replaceAll("[{}\\[\\]]","");
            int j= 0;
            for (String row: rep.split("\\|",8)) { // row-by-row
                for (int i= 0; i < row.length(); i++) {
                    if (i == 8)
                        throw new IllegalArgumentException("invalid board representation");
                    int piece= row.charAt(i);
                    if (piece == 'w')
                        setContents(i, j,  1);
                    else if (piece == 'b')
                        setContents(i, j, -1);
                }
                j++;
            }
        }
    }

    // Clone the current object
    public BoardState deepCopy() {
        BoardState copy= new BoardState();
        for (int i= 0; i < 8; i++)
            for (int j= 0; j < 8; j++)
                copy.setContents(i, j, getContents(i,j));
        copy.colour= colour;
        return copy;
    }
    // Helpers to save and restore valid board states
    private int oldColour;
    private int[][] oldBoard;
    public void save() {
        oldBoard= new int[8][];
        for (int i= 0; i < 8; i++)
            oldBoard[i]= board[i].clone();
        oldColour= colour;
    }
    public void restore() {
        if ((oldBoard == null) || (oldColour == 0))
            throw new IllegalStateException("no valid board state was saved");
        board= oldBoard;
        colour= oldColour;
    }

    // Tests whether the game is over
    public boolean gameOver() {
        boolean ans= false;
        if (getRegularMoves().isEmpty()) {
            colour= -colour;              // change colour temporarily
            if (getRegularMoves().isEmpty())
                ans= true;
            colour= -colour;               // change colour back
        }
        return ans;
    }
    // Report end of game status
    public String resultString() {
        String ans;
        int[] totals= getTotals();
        int white= totals[0];
        int black= totals[1];
        if (white > black)
            ans= "White wins";
        else if (white < black)
            ans= "Black wins";
        else
            ans= "Draw";
        ans+= " [White: " + white + "; Black: " + black + "]";
        return ans;
    }

    // Returns the list of legal moves for current player
    public ArrayList<Move> getLegalMoves() {
        ArrayList<Move> ans= getRegularMoves();
        if (ans.isEmpty())
            // No regular moves, so skip-move is legal
            ans.add(new Move());
        return ans;
    }
    // Returns the list of legal regular moves for current player (excluding skip)
    public ArrayList<Move> getRegularMoves() {
        ArrayList<Move> ans= new ArrayList<Move>();
        for (int i= 0; i < 8; i++)
            for (int j= 0; j < 8; j++) {
                Move move= new Move(i,j);
                if (checkLegalMove(move))
                    ans.add(move);
            }
        return ans;
    }

    // Helpers to check and make legal moves
    public boolean checkLegalMove(Move move) {
        // The null object is illegal
        if (move == null)
            return false;
        // Skip is legal iff no regular moves
        else if (move.isSkip())
            return getRegularMoves().isEmpty();
        // Otherwise, check regular move is okay
        return
            // There mustn't be anything already there
            (getContents(move.x,move.y) == 0) &&
            // The move must represent a proper bracketing in one of the eight directions
            ((checkLeft(move)     != -1) || (checkRight(move)     != -1) ||
             (checkUp(move)       != -1) || (checkDown(move)      != -1) ||
             (checkUpLeft(move)   != -1) || (checkUpRight(move)   != -1) ||
             (checkDownLeft(move) != -1) || (checkDownRight(move) != -1));
    }
    public void makeLegalMove(Move move){
        if (!move.isSkip()) { // Non-skip move
            int end;
            end= checkLeft(move);
            if (end != -1) // Change squares to left
                for (int i= move.x; i > end; i--)
                    setContents(i,move.y,colour);
            end= checkRight(move);
            if (end != -1) // Change squares to right
                for (int i= move.x; i < end; i++)
                    setContents(i,move.y,colour);
            end= checkUp(move);
            if (end != -1) // Change squares above
                for (int j= move.y; j > end; j--)
                    setContents(move.x,j,colour);
            end= checkDown(move);
            if (end != -1) // Change squares below
                for (int j= move.y; j < end; j++)
                    setContents(move.x,j,colour);
            end= checkUpLeft(move);
            if (end != -1) // Change squares above left
                for (int i= move.x, j= move.y; i > end; i--, j--)
                    setContents(i,j,colour);
            end= checkUpRight(move);
            if (end != -1) // Change squares above right
                for (int i= move.x, j= move.y; i < end; i++, j--)
                    setContents(i,j,colour);
            end= checkDownLeft(move);
            if (end != -1) // Change squares below left
                for (int i= move.x, j= move.y; i > end; i--, j++)
                    setContents(i,j,colour);
            end= checkDownRight(move);
            if (end != -1) // Change squares below right
                for (int i= move.x, j= move.y; i < end; i++, j++)
                    setContents(i,j,colour);
        }
        colour= -colour; // Always change player
    }

    // Return smallest value of i < x st board[i,y] is colour and board[a,y]
    // is opposite to colour for all a (i < a < x); return -1 if none in range
    private int checkLeft(Move move) {
        int i= move.x-1;
        while ((i > 0) && (getContents(i,move.y) == -colour)) // Check almost to start
            i--;
        if ((i < move.x-1) && (getContents(i,move.y) == colour))
            return i;
        else
            return -1;
    }
    // Return largest value of i > x st board[i,y] is colour and board[a,y]
    // is opposite to colour for all a (i > a > x); return -1 if none in range
    private int checkRight(Move move) {
        int i= move.x+1;
        while ((i < 7) && (getContents(i,move.y) == -colour)) // Check almost to end
            i++;
        if ((i > move.x+1) && (getContents(i,move.y) == colour))
            return i;
        else
            return -1;
    }

    // Return smallest value of j < y st board[x,j] is colour and board[x,b]
    // is opposite to colour for all b (j < b < y); return -1 if none in range
    private int checkUp(Move move) {
        int j= move.y-1;
        while ((j > 0) && (getContents(move.x,j) == -colour)) // Check almost to start
            j--;
        if ((j < move.y-1) && (getContents(move.x,j) == colour))
            return j;
        else
            return -1;
    }
    // Return largest value of j > y st board[x,j] is colour and board[x,b]
    // is opposite to colour for all b (j > b > y); return -1 if none in range
    private int checkDown(Move move) {
        int j= move.y+1;
        while ((j < 7) && (getContents(move.x,j) == -colour))   // Check almost to end
            j++;
        if ((j > move.y+1) && (getContents(move.x,j) == colour))
            return j;
        else
            return -1;
    }

    // Return smallest i < x st board[i,y-(x-i)] is colour and board[x-a,y-a]
    // is opposite to colour for all a (i < x-a < x); return -1 if none in range
    private int checkUpLeft(Move move) {
        int i= move.x-1;
        int j= move.y-1;
        while ((i > 0) && (j > 0) && (getContents(i,j) == -colour)) {
            i--;
            j--;
        }
        if ((i < move.x-1) && (getContents(i,j) == colour))
            return i;
        else
            return -1;
    }
    // Return largest i > x st board[i,y-(x-i)] is colour and board[x+a,y-a]
    // is opposite to colour for all a (i > x+a > x); return -1 if none in range
    private int checkUpRight(Move move) {
        int i= move.x+1;
        int j= move.y-1;
        while ((i < 7) && (j > 0) && (getContents(i,j) == -colour)) {
            i++;
            j--;
        }
        if ((i > move.x+1) && (getContents(i,j) == colour))
            return i;
        else
            return -1;
    }

    // Return smallest i < x st board[i,y+(x-i)] is colour and board[x-a,y+a]
    // is opposite to colour for all a (i < x-a < x); return -1 if none in range
    private int checkDownLeft(Move move) {
        int i= move.x-1;
        int j= move.y+1;
        while ((i > 0) && (j < 7) && (getContents(i,j) == -colour)) {
            i--;
            j++;
        }
        if ((i < move.x-1) && (getContents(i,j) == colour))
            return i;
        else
            return -1;
    }
    // Return largest i > x st board[i,y+(x-i)] is colour and board[x+a,y+a]
    // is opposite to colour for all a (i > x+a > x); return -1 if none in range
    private int checkDownRight(Move move) {
        int i= move.x+1;
        int j= move.y+1;
        while ((i < 7) && (j < 7) && (getContents(i,j) == -colour)) {
            i++;
            j++;
        }
        if ((i > move.x+1) && (getContents(i,j) == colour))
            return i;
        else
            return -1;
    }
}

/* vim:set et ts=4 sw=4: */
