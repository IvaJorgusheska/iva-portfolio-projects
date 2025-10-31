/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

public abstract class MoveChooser {
    // The name identifies the agent on the UI
    public final String displayName;
    // Depth of minimax search ie difficulty level for non-human agent
    public final int searchDepth;

    // Make string representation for UI, debug, etc
    @Override
    public String toString() {
        return displayName;
    }

    // Constructor records chosen agent name and requested search depth
    public MoveChooser(String name, int depth) {
        displayName= name;
        searchDepth= depth;
    }

    // Report human agent to game UI
    public final boolean isHuman() {
        return displayName.equals("Human");
    }

    /**
     * Concrete classes need to implement chooseMove(BoardState,Move)
     *
     * @param   boardState  The current state of the game board
     *                      This parameter is ignored by a human agent
     *
     * @param   hint        Skip move or board location clicked on the UI
     *                      This parameter is ignored by non-human agents
     *
     * @return  The move chosen by the agent
     */
    abstract public Move chooseMove(BoardState boardState, Move hint);

    /**
     * Concrete classes need to implement boardEval(BoardState)
     *
     * @param   boardState  The current state of the game board
     *
     * @return  The agent's evaluation of the board
     */
    abstract public int boardEval(BoardState boardState);
}

/* vim:set et ts=4 sw=4: */
