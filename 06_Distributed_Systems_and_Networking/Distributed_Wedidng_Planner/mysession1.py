#!/usr/bin/python3

import reservationapi
import configparser

# Load the configuration file containing the URLs and keys
config = configparser.ConfigParser()
config.read("api.ini")

# Create an API object to communicate with the hotel API
hotel  = reservationapi.ReservationApi(config['hotel']['url'],
                                       config['hotel']['key'],
                                       int(config['global']['retries']),
                                       float(config['global']['delay']))

# Your code goes here

# Perform operations as explained in Tasks 3.1, 3.2, 3.3, and 3.4
def main():
    try:
        #make sure we don't exceed the maximum number of reservations before we start with the booking
        held_slots = hotel.get_slots_held()
        if len(held_slots) > 1:
            to_release = sorted(held_slots, key=lambda x: int(x['id']))[1]
            hotel.release_slot(to_release['id'])


        # Task 3.3: Finding free slots
        available_slots = hotel.get_slots_available()
        if available_slots:
            print("Available slots:", available_slots)
        else:
            print("No available slots")


        # Task 3.1: Checking and reserving a slot
        available_slots = hotel.get_slots_available()
        if available_slots:
            while(True):
                available_slots = hotel.get_slots_available()
                slot_id = available_slots[0]['id']  # Assuming we choose the first available slot
                reservation_id = hotel.reserve_slot(slot_id)
                if reservation_id:
                    print("Slot with ID: ", reservation_id, " reserved successfully.")
                    break
                else:
                    print("Failed to reserve slot")
        else:
            print("No available slots")

  
        # Task 3.4: Checking slots reserved by you
        held_slots = hotel.get_slots_held()
        if held_slots:
            print("Slots held:", held_slots)
        else:
            print("No slots held")


        # Task 3.2: Cancelling a reservation
        held_slots = hotel.get_slots_held()
        if held_slots:
            if len(held_slots) > 1:
                print("We release the slot with worse date.")
                to_release = sorted(held_slots, key=lambda x: int(x['id']))[1]
                if (hotel.release_slot(to_release['id'])):
                    print("Slot ", to_release," cancelled successfully.")
                else:
                    print("Failed to cancel slot")
            else:
                slot_id_to_cancel = held_slots[0]['id']  
                if hotel.release_slot(slot_id_to_cancel):
                    print("Slot", slot_id_to_cancel, "canceled successfully")
                else:
                    print("Failed to cancel slot")    
        else:
            print("No slots held")

      

    except Exception as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    main()