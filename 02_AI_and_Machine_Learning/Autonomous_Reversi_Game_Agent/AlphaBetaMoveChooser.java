import java.util.ArrayList;



/**
 * Solution code for Comp24011 Reversi lab
 *
 * @author c00714ij
 */

public class AlphaBetaMoveChooser extends MoveChooser {
    /**
     * MoveCooser implementation AlphaBetaMoveChooser(int)
     *
     * @param   searchDepth The requested depth for minimax search
     */
   
    private static int first = 0;
    private  static int white = 1;
    public AlphaBetaMoveChooser(int searchDepth) {
        // Add object initialisation code...
        super("MyAwesomeAgent",searchDepth);
    }

    /**
     * Need to implement chooseMove(BoardState,Move)
     *
     * @param   boardState  The current state of the game board
     *
     * @param   hint        Skip move or board location clicked on the UI
     *                      This parameter should be ignored!
     *
     * @return  The move chosen by alpha-beta pruning as discussed in the course
     */
    public Move chooseMove(BoardState boardState, Move hint) {
        // Add alpha-beta pruning code...
        

        
        int alpha = Integer.MIN_VALUE;; 
        int beta = Integer.MAX_VALUE;

        ArrayList<Move> moves = boardState.getLegalMoves();
        int size = moves.size();

        

        Move bestMove = null;

if (first == 0){
     if (boardState.getScore() <0){
        change_white(1);
     }
     if(boardState.getScore() == 0){
        change_white(0);
     }
 }



    if (white==1){
            int bestValue = Integer.MIN_VALUE;
            for (Move move : moves){
             

                boardState.save();
                //BoardState possibleState = boardState.deepCopy();

                //possibleState.makeLegalMove(move);
                boardState.makeLegalMove(move);
                
                //minimax search
                //value is minimax search
                int value = minimax(boardState, alpha, beta, searchDepth - 1, false);

                if (value>bestValue){
                    bestValue = value;
                    bestMove = move;
                }

                if (bestValue > alpha){
                    alpha = bestValue;
                }

                if(alpha >= beta){
                     boardState.restore();
                    break;
                }
                boardState.restore();
        } 
        return bestMove;
    }
    else{
        int bestValue = Integer.MAX_VALUE;
        for (Move move : moves){
             

                boardState.save();
                //BoardState possibleState = boardState.deepCopy();

                //possibleState.makeLegalMove(move);
                boardState.makeLegalMove(move);
                
                //minimax search
                //value is minimax search
                int value = minimax(boardState, alpha, beta, searchDepth - 1, true);

                if (value<bestValue){
                    bestValue = value;
                    bestMove = move;
                }

                if (bestValue < beta){
                    beta = bestValue;
                }

                if(alpha >= beta){
                    boardState.restore();
                    break;
                }
                boardState.restore();
        }
         return bestMove;
    }  

}
  


    public int minimax(BoardState possibleState, int alpha, int beta, int depth, boolean isMax){

        ArrayList<Move> next_moves = possibleState.getLegalMoves();
        if (depth == 0 || next_moves.isEmpty()){
            return boardEval(possibleState);
        }

        if (isMax == true){
            int maxVal = Integer.MIN_VALUE;
            Move best = null;
            BoardState nextState2 = possibleState.deepCopy();
            for (Move move : next_moves){
                 //BoardState nextState2 = possibleState.deepCopy();
                 nextState2.save();
                 nextState2.makeLegalMove(move);

                 int res = minimax(nextState2, alpha, beta, depth - 1, false);

                 if (res > maxVal){
                    maxVal = res;
                    best = move;
                 }
                 
                 if (res >alpha){
                    alpha = res;
                 }
                 if (alpha >= beta){
                    nextState2.restore();
                    break;
                 }
                  nextState2.restore();
                 
            }
           
            return maxVal;
        }
        else{
            int minVal = Integer.MAX_VALUE;
            Move worst = null;
            BoardState nextState = possibleState.deepCopy();
            for (Move move : next_moves){
                 //BoardState nextState = possibleState.deepCopy();
                 nextState.save();
                 nextState.makeLegalMove(move);

                int res = minimax(nextState, alpha, beta, depth - 1, true);
                
                if (res < minVal){
                    minVal = res;
                    worst = move;
                }
                
                if (res < beta){
                    beta = res;
                }

                if (alpha >= beta){
                    nextState.restore();
                    break;
                }
                 nextState.restore();
               
            }
        
            return minVal;
        }
        //return boardEval(possibleState);
    }



    public void change_white(int make){
        first = 1;
        if (make == 1){
            white = 1;
        }
        else{
            white = 0;
        }

    }




    /**
     * Need to implement boardEval(BoardState)
     *
     * @param   boardState  The current state of the game board
     *
     * @return  The value of the board using Norvig's weighting of squares
     */
    public int boardEval(BoardState boardState) {
        // Add board evaluation code...
        int [][] values = {
            { 120, -20,  20,  5,  5, 20, -20, 120},
            { -20, -40,  -5, -5, -5, -5, -40, -20},
            {  20,  -5,  15,  3,  3, 15,  -5,  20},
            {   5,  -5,   3,  3,  3,  3,  -5,   5},
            {   5,  -5,   3,  3,  3,  3,  -5,   5},
            {  20,  -5,  15,  3,  3, 15,  -5,  20},
            { -20, -40,  -5, -5, -5, -5, -40, -20},
            { 120, -20,  20,  5,  5, 20, -20, 120}
        };

        int ans = 0;

        for (int i=0; i<8; i++){
            for (int j=0; j<8; j++){
                if (boardState.getContents(i, j) == 1){
                    //white
                    ans = ans + values[i][j];
                }

                if (boardState.getContents(i, j) == -1) {
                    //black   
                    ans = ans - values[i][j];
                }
            }
        }


        return ans;
    }
}

/* vim:set et ts=4 sw=4: */


