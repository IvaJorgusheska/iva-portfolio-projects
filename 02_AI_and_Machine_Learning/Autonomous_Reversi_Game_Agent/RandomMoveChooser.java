/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

import java.util.ArrayList;
import java.util.Random;

public class RandomMoveChooser extends MoveChooser {
    // This agent only has no difficulty levels
    private Random rand;

    // Initialise random number generator based on seed/current time & machine
    public RandomMoveChooser(long seed) {
        super("Random",-1);
        if (seed == 0) {
            seed= System.currentTimeMillis();
            String hostname= System.getenv("HOSTNAME");
            if (hostname == null)
                hostname= "localhost";
            seed+= (long) Math.pow(hostname.hashCode(),2);
        }
        rand= new Random(seed);
    }

    // Choose a random legal move
    public Move chooseMove(BoardState boardState, Move hint) {
        ArrayList<Move> moves= boardState.getLegalMoves();
        return moves.get( rand.nextInt(moves.size()) );
    }

    // Dummy implementation of board evaluation
    public int boardEval(BoardState boardState) {
        return -1;
    }
}

/* vim:set et ts=4 sw=4: */
