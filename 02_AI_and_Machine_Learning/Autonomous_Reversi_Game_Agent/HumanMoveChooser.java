/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

public class HumanMoveChooser extends MoveChooser {
    // This agent only has no difficulty levels
    public HumanMoveChooser() {
        super("Human",-1);
    }

    // Always report move chosen by user on the UI
    public Move chooseMove(BoardState boardState, Move hint) {
        return hint;
    }

    // Dummy implementation of board evaluation
    public int boardEval(BoardState boardState) {
        return -1;
    }
}

/* vim:set et ts=4 sw=4: */
