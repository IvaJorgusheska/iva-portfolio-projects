"""
Iva Jorgusheska, UID: 11114620
The following commands are intented to be executed in a directory containing the ex2utils.py, myserver.py and myclient.py.
To test the functionality of the server via telnet. Run the server in a terminal with the command 
python3 myserver.py localhost 8090
In other terminals, run the command 
telnet localhost 8090
You will connect a server and get a message to register. You must register first using '$register yourName',
otherwise you will get invalid command message. Your name should contain one word only.
You can also $quit before you register. Once you register, you can write anything in the terminal.
If you do not write a valid command, but a simple message without $, in the server terminal you can see it is seen it 
as a normal message, not a command. The following commands are available:
-$messageAll ___Message____
-$messageUser   ___NameOfTheRecipient___   ___message___
-$listAllUsers
-$listAllCommands
-$quit

When it begins with, $, in the server terminal we can see it recognizes them as commands, and appropriate parameters.
If you enter invalid message, you will get appropriate message. 
Log in the server from few other terminals. And then test the functionality by writing $messageAll and a message, or $messageUser theName and the mesage.
If you do not know the exact name of the recipient, you can $listAllUsers to see it. you can also $listAllCommands whenever, or quit.

To test the functionality with myclient.py, run the myserver.py in one terminal, and myclient.py in few others. 
python3 myserver.py localhost 8090
python3 myclient.py localhost 8090
Make sure to run them on the same port.
It contains a GUI which makes it easy for the user. You first register, then you are opened another window. 
Your screen name should be from one word only. The first 2 lines give
you the option to message a single user by enetering his name and the message. Use the name you entered when registering from some 
other terminal as the recipient name. The second optio gives you an option to message all users, by entering 
the message and clicking the button. As it is written, you can write quit/listAllUsers in the box down. Note that here you do not need to include $.
In the output box down you are informed about any recieved messages or feedback from the server. After you write quit, you disconnect from the server
and you need to close the window.
I have listed the available commands, so there is no need for the listAllCommands function in the GUI. Its functionality can
be tested with telnet.
"""



import sys
import tkinter as tk
from tkinter import messagebox

from ex2utils import Client

class IRCClient(Client):
    def __init__(self):
        super().__init__()
        self.notification = ""

    def onMessage(self, socket, message):
        print(message)
        self.notification = message
        return "yes"

def open_terminal_window():
    # Close the initial registration window
    window.destroy()

    terminal_window = tk.Tk()
    terminal_window.title("IRC Terminal")
    terminal_window.configure(bg="#FFC0CB")
    terminal_window.geometry("900x600")  # Set the size of the window

    # Create and pack widgets for the terminal window
    label_recipient = tk.Label(terminal_window, text="Recipient:", bg="#FFC0CB", font=("Arial", 12))
    label_recipient.grid(row=0, column=0, padx=10, pady=5)

    entry_recipient = tk.Entry(terminal_window, width=20, font=("Arial", 12))
    entry_recipient.grid(row=0, column=1, padx=10, pady=5)

    label_message = tk.Label(terminal_window, text="Message:", bg="#FFC0CB", font=("Arial", 12))
    label_message.grid(row=1, column=0, padx=10, pady=5)

    entry_message = tk.Entry(terminal_window, width=50, font=("Arial", 12))
    entry_message.grid(row=1, column=1, padx=10, pady=5)

    #the functionality of the first two text boxes, making sure both of them are filled if you want to message a certain user
    def send_message_user():
        recipient = entry_recipient.get().strip()
        message = entry_message.get().strip()
        if recipient and message:
            formatted_message = f"$messageUser {recipient} {message}"
            client.send(formatted_message.encode())
            messages_text.insert(tk.END, f"You to {recipient}: {message}\n")
            entry_message.delete(0, tk.END)
        else:
            messagebox.showwarning("Incomplete Message", "Recipient or message is missing.")

    button_send_message_user = tk.Button(terminal_window, text="Message User", command=send_message_user, font=("Arial", 12), bg="#FF69B4", bd=0, relief=tk.FLAT, width=15)
    button_send_message_user.grid(row=0, column=2, padx=10, pady=5)

    label_message_all = tk.Label(terminal_window, text="Message for All Users:", bg="#FFC0CB", font=("Arial", 12))
    label_message_all.grid(row=2, column=0, padx=10, pady=5)

    entry_message_all = tk.Entry(terminal_window, width=50, font=("Arial", 12))
    entry_message_all.grid(row=2, column=1, padx=10, pady=5)

    #make sure there is a message you want to send to all the users if the button is pressed
    def send_message_all():
        message = entry_message_all.get().strip()
        if message:
            formatted_message = f"$messageAll {message}"
            client.send(formatted_message.encode())
            messages_text.insert(tk.END, f"You to all users: {message}\n")
            entry_message_all.delete(0, tk.END)
        else:
            messagebox.showwarning("Incomplete Message", "Message for all users is missing.")

    button_send_message_all = tk.Button(terminal_window, text="Message All Users", command=send_message_all, font=("Arial", 12), bg="#FF69B4", bd=0, relief=tk.FLAT, width=15)
    button_send_message_all.grid(row=2, column=2, padx=10, pady=5)

    label_commands = tk.Label(terminal_window, text="Commands Available:", bg="#FFC0CB", font=("Arial", 12))
    label_commands.grid(row=3, column=0, columnspan=3, padx=10, pady=(20, 5))

    commands_text = tk.Text(terminal_window, height=5, width=50, wrap=tk.WORD, font=("Arial", 12))
    commands_text.grid(row=4, column=0, columnspan=3, padx=10, pady=5)

    commands = [
        "messageUser <username> <message>",
        "messageAll <message>",
        "listAllUsers",
        "quit"
    ]

    for command in commands:
        commands_text.insert(tk.END, f"{command}\n")

    label_quit = tk.Label(terminal_window, text="Type 'quit' to exit or 'listAllUsers' to see all users", bg="#FFC0CB", font=("Arial", 10))
    label_quit.grid(row=5, column=0, columnspan=3, padx=10, pady=5)

    entry_command = tk.Entry(terminal_window, width=50, font=("Arial", 12))
    entry_command.grid(row=6, column=1, padx=10, pady=5)

    #field for quit or listAllUsers
    def send_command():
        command = entry_command.get().strip()
        command = "$"+command
        if command:
            client.send(command.encode())
            entry_command.delete(0, tk.END)
        else:
            messagebox.showwarning("Incomplete Command", "Command is missing.")

    button_send_command = tk.Button(terminal_window, text="Send Command", command=send_command, font=("Arial", 12), bg="#FF69B4", bd=0, relief=tk.FLAT, width=15)
    button_send_command.grid(row=6, column=2, padx=10, pady=5)

    messages_text = tk.Text(terminal_window, height=10, width=50, wrap=tk.WORD, font=("Arial", 12))
    messages_text.grid(row=7, column=0, columnspan=3, padx=10, pady=10)

    def update_terminal():
        if client.notification:
            messages_text.insert(tk.END, f"Server: {client.notification}\n")
            client.notification = ""  # Reset notification after displaying

        terminal_window.after(100, update_terminal)

    update_terminal()  # Start the update loop

# Parse the IP address and port you wish to connect to.
ip = sys.argv[1]
port = int(sys.argv[2])

# Create an IRC client.
client = IRCClient()

# Start server
client.start(ip, port)

def register():
    name = entry_name.get()
    name_register = "$register " + name
    client.send(name_register.encode())

    while client.notification == "":
        window.update_idletasks()

    if client.notification == "The name already exists.":
        messagebox.showerror("Error", "The name is already occupied. Please choose a different name.")
    elif client.notification == "Enter a valid command":
        messagebox.showerror("Error", "The name must contain only one word.")
    else:
        messagebox.showinfo("Success", "Registration successful!")
        open_terminal_window()

# Create the main window
window = tk.Tk()
window.title("User Registration")
window.configure(bg="#FFC0CB")
window.geometry("400x300")  # Set the size of the window

# Create and pack widgets
label_instruction = tk.Label(window, text="Enter your name:", bg="#FFC0CB", font=("Arial", 12))
label_instruction.pack(pady=10)

entry_name = tk.Entry(window, font=("Arial", 12))
entry_name.pack(pady=5)

button_register = tk.Button(window, text="Register", command=register, font=("Arial", 12), bg="#FF69B4", bd=0, relief=tk.FLAT, width=15)
button_register.pack(pady=10)

# Start the main loop
window.mainloop()

# Stop the client
client.stop()
