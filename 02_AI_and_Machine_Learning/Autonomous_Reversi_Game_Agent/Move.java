/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

public class Move {
    // Represents a move as a pair of coordinates x,y in the range 0 to 7.
    // If x is negative, it is the skip move (cannot go, so pass a turn)
    public int x;
    public int y;

    // https://stackoverflow.com/questions/14677993/
    @Override
    public int hashCode() {
        return (x << 16) + y;
    }
    @Override
    public boolean equals(Object obj) {
        if (obj instanceof Move) {
            Move alt= (Move) obj;
            return (x == alt.x) && (y == alt.y);
        }
        return false;
    }
    // Make string representation for debug purposes, etc
    @Override
    public String toString() {
        return "(" + x + "," + y + ")";
    }

    // Check if coordinates are inside board
    private static boolean checkOnBoard(int i, int j) {
        return (j >= 0) && (i < 8) && (j >= 0) && (j < 8);
    }
    // Constructor to create a move to square (i,j) (player can go)
    public Move(int i, int j) {
        if (!checkOnBoard(i,j))
            throw new IllegalArgumentException("coordinates not on board");
        x= i;
        y= j;
    }

    // Indicates whether the move is the skip move
    public boolean isSkip() {
        return (x < 0);
    }
    // Constructor to create the skip move (player cannot go)
    public Move() {
        x= -1; // Bad values to create errors
        y= -1;
    }
}

/* vim:set et ts=4 sw=4: */
