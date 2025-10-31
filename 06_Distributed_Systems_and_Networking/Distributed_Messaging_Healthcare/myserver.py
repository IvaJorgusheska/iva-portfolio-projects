#Iva Jorgusheska, UID: 11114620
import sys
from ex2utils import Server


# Create an echo server class
class MyServer(Server):

	def onStart(self):
		print("Echo server has started")
		self.nrOfClients = 0 
		self.sockets = []    #list to store the sockets of all the connected users
		self.users = dict()  #dictionary to keep the names of the user connected on a certain socket
		
		
	def onMessage(self, socket, message):
		# This function takes two arguments: 'socket' and 'message'.
		#     'socket' can be used to send a message string back over the wire.
		#     'message' holds the incoming message string (minus the line-return).
	
		# convert the string to an upper case version
		# message = "You successfully entered a message."

		if message[0] == "$":
			print("Command entered.")
			command = message[1:]
			commandSplit = command.split()
			print("Command is: " + commandSplit[0])
			if len(commandSplit) >= 2:
				print("Parameters are: " + ", ".join(commandSplit[1:]))
		else:
			print("Message entered.")


		#make sure the user registers first
		if (self.firstMessage == 1):
			messageSplit = message.split()
			if messageSplit[0] == "$quit":
				disconnectMess = "You successfully disconnected the server"
				disconnectMess = disconnectMess.encode()
				socket.send(disconnectMess)
				socket.close()
				return False
			elif len(messageSplit) == 2:
				if (messageSplit[0] == "$register"):
					name = messageSplit[1]
					if name in self.users.values():
						loginRequiredMessage = ("The name already exists.")
						loginRequiredMessage = loginRequiredMessage.encode()
						socket.send(loginRequiredMessage)
					else:
						self.firstMessage = 0
						self.users[socket] = name
						registerSuccMessage = ("You have registered successfully.")
						registerSuccMessage = registerSuccMessage.encode()
						socket.send(registerSuccMessage)
				else:
					loginRequiredMessage = ("You must register first.")
					loginRequiredMessage = loginRequiredMessage.encode()
					socket.send(loginRequiredMessage)
			else:
				validCommandMessage = ("Enter a valid command")
				validCommandMessage = validCommandMessage.encode()
				socket.send(validCommandMessage)
		else:
			messageSplit = message.split()
			if (messageSplit[0] == "$messageAll"):
				if len(messageSplit) > 1:
					sendMessage = (self.users[socket] + " sent evryone a message: " + " ".join(messageSplit[1:]))
					sendMessage = sendMessage.encode()
				for soc in self.sockets:
					if soc != socket:
						soc.send(sendMessage)
					else:
						sentMessage = ("Message sent succesfully to all the users.")
						sentMessage = sentMessage.encode()
						soc.send(sentMessage)
			elif messageSplit[0] == "$quit":
				disconnectMess = "You successfully disconnected the server"
				disconnectMess = disconnectMess.encode()
				socket.send(disconnectMess)
				socket.close()
				return False
			elif (messageSplit[0] == "$messageUser"):
				if (len(messageSplit) < 3):
					threeParamMess = ("You need to enter the screen name of the user you want to message and then your actual message, eg: $messageUser Marry how are you?")
					threeParamMess = threeParamMess.encode()
					socket.send(threeParamMess)
				else:
					recipient = messageSplit[1]
					sendMessage = (self.users[socket] + " sent you a message: " + " ".join(messageSplit[2:]))
					sendMessage = sendMessage.encode()
					if recipient in self.users.values():
						for sock in self.users.keys():
							if self.users[sock] == recipient:
								sock.send(sendMessage)
								break
						sentMessage = ("Message sent succesfully to " + recipient + ".")
						sentMessage = sentMessage.encode()
						socket.send(sentMessage)
					else:
						invalidRecipientMessage = (recipient + " is not a user connected to the server.")
						invalidRecipientMessage = invalidRecipientMessage.encode()
						socket.send(invalidRecipientMessage)

			elif (messageSplit[0] == "$listAllUsers"):
				userNames = ""
				for soc in self.sockets:
					if self.users[soc] is not None:
						userNames = userNames + self.users[soc] + ", "
				userNames = userNames[:-2]  # Remove the last two characters (', ')
				if (userNames == ""):
					listUsersMessage = ("There are no users registered to the server yet.")
				else:
					listUsersMessage = ("Users connected to the server: " + userNames)
				listUsersMessage = listUsersMessage.encode()
				socket.send(listUsersMessage)
			elif (messageSplit[0] == "$listAllCommands"):
				listCommandsMess = ("Commands available:\n $messageAll ______ - ___ is the string you want to send as a messag to all the users connected to the server. \n $messageUser theNameOfTheRecipient YourMessage - when you want to message a specific user connected to the server \n $listAllUsers - lists the names of all the users connected to the server \n $listAllCommands - lists all the command available. \n $quit - disconnects from the server \n Note that all messages beginning with this sign $ are considered as commands.")
				listCommandsMess = listCommandsMess.encode()
				socket.send(listCommandsMess)
			elif (message[0] == "$"):
				validCommandMessage = ("You entered an invalid command")
				validCommandMessage = validCommandMessage.encode()
				socket.send(validCommandMessage)







		# # Just echo back what we received
		# message=message.encode()
		# socket.send(message)
		
		# Signify all is well
		return True
		  
	#initializes the first message flag, and updates the total number of users
	def onConnect(self, socket):
		self.firstMessage = 1
		self.sockets.append(socket)
		print("Connected to the server.")
		self.nrOfClients = self.nrOfClients + 1 
		print("Number of  clients connected to the server: " , self.nrOfClients)
		connectMessage = "Number of  clients connected to the server: " + str(self.nrOfClients)

		connectMessage = connectMessage.encode()

		registerMessage = ("Enter $register and your name")
		registerMessage = registerMessage.encode()

		for soc in self.sockets:
			soc.send(connectMessage)

		socket.send(registerMessage)

		#return True
 		


	def onDisconnect(self, socket):
		if socket in self.sockets:
			self.sockets.remove(socket)
		print("Disconnected from the server.")
		self.nrOfClients -= 1
		print("Number of  clients connected to the server: " , self.nrOfClients)

		disconnectMessage = "Number of  clients connected to the server: " + str(self.nrOfClients)

		disconnectMessage = disconnectMessage.encode()
		

		for soc in self.sockets:
			soc.send(disconnectMessage)

		for key, value in self.users.items():
			if key == socket:
				del self.users[key]
				break 
		#TO DO - REMOVE IT FROM USERS, SOCKETS
		#return True

#    This is called when a client's connection is terminated. As with onConnect(),
#    the connection's socket is provided as a parameter. This is called regardless of
#    who closed the connection.



# Parse the IP address and port you wish to listen on.
ip = sys.argv[1]
port = int(sys.argv[2])

# Create an echo server.
server = MyServer()

# If you want to be an egomaniac, comment out the above command, and uncomment the
# one below...
#server = EgoServer()

# Start server
server.start(ip, port)
