/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

import java.util.ArrayList;

public class FirstMoveChooser extends MoveChooser {
    // This agent only has no difficulty levels
    public FirstMoveChooser() {
        super("First",-1);
    }

    // Always choose the first legal move
    public Move chooseMove(BoardState boardState, Move hint) {
        ArrayList<Move> moves= boardState.getLegalMoves();
        return moves.get(0);
    }

    // Dummy implementation of board evaluation
    public int boardEval(BoardState boardState) {
        return -1;
    }
}

/* vim:set et ts=4 sw=4: */
