/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

public class Othello {
    // Default player to start game
    public static final int startColour= -1;
    // Default depth of minimax search
    public static final int searchDepth= 6;
    // Default seed for random number generation
    public static final long randomSeed= 0l;

    public static void main(String[] args) {
        BoardState initialState;
        MoveChooser whiteChooser;
        MoveChooser blackChooser;

        // Load boardState from command line if argument given
        if (args.length > 0)
            initialState= new BoardState(args[0]);
        else {
            initialState= new BoardState("");
            initialState.colour= startColour;
        }

        // Choose the two players.
        // You can experiment with different agents!
        whiteChooser= new
            //FirstMoveChooser()
            RandomMoveChooser(randomSeed)
            //AlphaBetaMoveChooser(searchDepth)
            //HumanMoveChooser()
            ;
        blackChooser= new
            //FirstMoveChooser()
            //RandomMoveChooser(randomSeed)
            //AlphaBetaMoveChooser(searchDepth)
            HumanMoveChooser()
            ;

        // Run interactive game UI
        OthelloDisplay othelloDisplay= new
            OthelloDisplay(initialState,whiteChooser,blackChooser);
        othelloDisplay.startGame();
    }
}

/* vim:set et ts=4 sw=4: */
