/**
 * Java code for Comp24011 Reversi lab
 *
 * @author Mbassip2
 * @author a21674fl
 *
 * Copyright 2023; please do not distribute!
 */

public final class MoveStats {
    // Timeout for chooseMove() implementation
    private static final int timeOut= 10000; // milliseconds
    private static final int sleepDelay= 200; // milliseconds

    // Move type with implementation statistics
    public long timeSpent; // nanoseconds
    public Move move;
    public boolean isLegal;
    public String errorMsg;

    // Make string representation for debug purposes, etc
    @Override
    public String toString() {
        return String.format(
                "Move %s, %s, %.1f secs, %s",
                (move == null)? "(null)" : move.toString(),
                (isLegal)? "valid" : "invalid",
                (double) timeSpent / 1e9d,
                (errorMsg == "")? "no error" : "error: " + errorMsg);
    }

    // https://stackoverflow.com/questions/9148899/
    private class ChooseMoveTask implements Runnable {
        private MoveChooser player;
        private BoardState boardState;
        private Move hint;
        public volatile Move move= null;
        public ChooseMoveTask(MoveChooser player, BoardState boardState, Move hint) {
            this.player= player;
            this.boardState= boardState;
            this.hint= hint;
        }
        @Override
        public void run() {
            move= player.chooseMove(boardState,hint);
        }
    }

    // https://stackoverflow.com/questions/6546193/
    private class ChooseMoveHandler implements Thread.UncaughtExceptionHandler {
        public volatile Throwable error= null;
        public ChooseMoveHandler() {
            super();
        }
        @Override
        public void uncaughtException(Thread t, Throwable e) {
            error= e;
        }
    }

    // Collect statistics of chooseMove() implementation
    public MoveStats(MoveChooser player, BoardState boardState, Move hint) {
        // set up support for chooseMove() timeout
        ChooseMoveTask task= new ChooseMoveTask(player,boardState,hint);
        ChooseMoveHandler taskHandler= new ChooseMoveHandler();
        Thread taskThread= new Thread(task);
        taskThread.setDefaultUncaughtExceptionHandler(taskHandler);
        Exception error= null;
        long taskStart, taskEnd;
        // measure chooseMove() implementation
        taskStart= System.nanoTime();
        try {
            taskThread.start();
            taskEnd= taskStart + (long) (timeOut * 1e6);
            while (taskThread.isAlive()) {
                if (System.nanoTime() > taskEnd) {
                    taskThread.interrupt();
                    break;
                }
                try {
                    Thread.currentThread().sleep(sleepDelay);
                } catch (InterruptedException e) {
                    continue;
                }
            }
        } catch (Exception e) {
            error= e;
        }
        timeSpent= System.nanoTime() - taskStart;
        // Record timeout or runtime exceptions; prioritise innermost errors
        if (taskHandler.error != null)
            errorMsg= taskHandler.error.toString()
                + " @ " + taskHandler.error.getStackTrace()[0].toString();
        else if (error != null)
            errorMsg= error.toString()
                + " @ " + error.getStackTrace()[0].toString();
        else if (taskThread.isInterrupted())
            errorMsg= "chooseMove() implementation timed out";
        else
            errorMsg= "";
        // Test and record the chosen move
        move= task.move;
        isLegal= boardState.checkLegalMove(move);
    }
}

/* vim:set et ts=4 sw=4: */
