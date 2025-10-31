#!/usr/bin/python3
#UID: 11114620
'''To use the booking services, just run this python file. The user friendly 
messages will guide you firther. The user is prompt to enter input at the end fo the script if he would like 
to keep checking for better dates once we found the optimal one so far. The user can keep rechecking how 
many times he would like. To keep checking, you need to press y. '''
import reservationapi
import configparser
import time


# Load the configuration file containing the URLs and keys
config = configparser.ConfigParser()
config.read("api.ini")

# Create an API object to communicate with the hotel API
hotel  = reservationapi.ReservationApi(config['hotel']['url'],
                                       config['hotel']['key'],
                                       int(config['global']['retries']),
                                       float(config['global']['delay']))


# Create an API object to communicate with the band API
band  = reservationapi.ReservationApi(config['band']['url'],
                                       config['band']['key'],
                                       int(config['global']['retries']),
                                       float(config['global']['delay']))



def check_common_slots():
    """Check the availability of common slots and display the first 20 common slots"""
    common_slots = []

    try:
        # Get the list of available slots for both the hotel and the band
        hotel_slots = hotel.get_slots_available()
        band_slots = band.get_slots_available()

        # Find common slots between the hotel and the band
        for slot in hotel_slots:
            if slot in band_slots:
                common_slots.append(slot)

        return common_slots

    except Exception as e:
        print(f"Error occurred while checking common slots: {e}")
        return []



def display_available_slots():
    """Display available slots for the band and hotel separately."""
    try:
        # Get the list of available slots for the hotel and the band
        hotel_slots = hotel.get_slots_available()
        band_slots = band.get_slots_available()

        # Display available slots for the hotel
        print("Available hotel slots:")
        if hotel_slots:
            for slot in hotel_slots:
                print(f"Slot ID: {slot['id']}")
        else:
            print("No available slots.")

        # Display available slots for the band
        print("\nAvailable band slots:")
        if band_slots:
            for slot in band_slots:
                print(f"Slot ID: {slot['id']}")
        else:
            print("No available slots.")

    except Exception as e:
        print(f"Error occurred while fetching available slots: {e}")



def book_earliest_common_slot(common_slots):
    """Book the earliest common slot, release other booked slots if any, and display booked slots"""
    try:
        if not common_slots:
            print("No common slots available.")
            return False

        
        earliest_slot = common_slots[0]

        hotel_slot_id = 0
        band_slot_id = 1
        
        # Book the earliest common slot for both the hotel and the band
        hotel_slot_id = hotel.reserve_slot(earliest_slot['id'])
        band_slot_id = band.reserve_slot(earliest_slot['id'])
        #make sure we booked the same slot for both the hotel and the band
        if (hotel_slot_id != band_slot_id):
            hotel.release_slot(hotel_slot_id)
            band.release_slot(band_slot_id)
            return None, None
    


        # Display booked slots
        
        if hotel_slot_id:
            print("Successfuly booked the earliest common slot. Newely booked slots:")
            print(f"Hotel slot: {hotel_slot_id}")
        if band_slot_id:
            print(f"Band slot: {band_slot_id}")

        return hotel_slot_id, band_slot_id

    except Exception as e:
        print(f"Error occurred while booking earliest common slot: {e}")
        return None, None


def recheck_for_better_bookings(common_slots, hotel_slot_id, band_slot_id):
    """Recheck for better bookings and release unmatched bookings"""
    try:
        if not common_slots:
            print("No common slots available.")
            return

        # Sort common slots by slot ID to find the earliest one
        earliest_slot = sorted(common_slots, key=lambda x: int(x['id']))[0]

        
        # If there are booked slots, check if there are better ones available
        if hotel_slot_id and band_slot_id:

            #we keep trying until we manage to book the earliest common slot
            while (True):
                common_slots = check_common_slots()
                if not common_slots:
                    break 
                better_hotel_slot, better_band_slot = book_earliest_common_slot(common_slots)
                if (better_hotel_slot != None and better_hotel_slot != False):
                    break
                else:
                    common_slots = check_common_slots()


            hotel_two = hotel.get_slots_held()
            band_two = band.get_slots_held()

            update_hotel = sorted(hotel_two, key=lambda x: int(x['id']))[0]
            update_band = sorted(band_two, key=lambda x: int(x['id']))[0]
            
            if better_hotel_slot and better_band_slot and (better_hotel_slot == better_band_slot) and update_hotel != hotel_slot_id:
                hotel.release_slot(hotel_slot_id)
                hotel_slot_id = better_hotel_slot
                band.release_slot(band_slot_id)
                released = band_slot_id
                band_slot_id = better_band_slot
                print(f"Better common slot found: {band_slot_id}, and the old slot with id: {released} released.")
            else:
                hotel.release_slot(better_hotel_slot)
                band.release_slot(better_band_slot)
        return hotel_slot_id, band_slot_id


    except Exception as e:
        print(f"Error occurred while rechecking for better bookings: {e}")
        return False



def main():
    while(True):
        try:
            time.sleep(1)


            hotel_slots_held = hotel.get_slots_held()
            band_slots_held = band.get_slots_held()
            hotel_slots_sorted = sorted(hotel_slots_held, key=lambda x: int(x['id']))
            band_slots_sorted = sorted(band_slots_held, key=lambda x: int(x['id']))
            
            print("Slots already booked by us, hotel slots: ",hotel_slots_held, "  band slots: ", band_slots_held)

            #make sure we keep only the slot with better date
            if len(hotel_slots_sorted) > 1:
                to_release = sorted(hotel_slots_sorted, key=lambda x: int(x['id']))[1]
                while(True):
                    if(hotel.release_slot(to_release['id'])):
                        break

            if len(band_slots_sorted) > 1:
                to_release = sorted(band_slots_sorted, key=lambda x: int(x['id']))[1]
                while(True):
                    if(band.release_slot(to_release['id'])):
                        break

            #if we have previously booked the hotel and the band for 
            #different dates we try to book the second one for the earlier date
            hotel_slots_held = hotel.get_slots_held()
            band_slots_held = band.get_slots_held()
            for slot in hotel_slots_held:
                if slot not in band_slots_held:
                    #we try to reserve the same slot for the band to get matching slots
                    if (band.reserve_slot(slot['id'])):
                        print(f"Successfully booked band for date {slot['id']} to match the hotel.")
                        continue
                    else:
                        while(True):
                            if(hotel.release_slot(slot['id'])):
                                break

            hotel_slots_held = hotel.get_slots_held()
            band_slots_held = band.get_slots_held()

            for slot in band_slots_held:
                if slot not in hotel_slots_held:
                    #we try to reserve the same slot for the hotel
                    if (hotel.reserve_slot(slot['id'])):
                        print(f"Successfully booked hotel for date {slot['id']} to match the band.")
                        continue
                    else:
                        while(True):
                            if(band.release_slot(slot['id'])):
                                break


            hotel_slots_held = hotel.get_slots_held()
            band_slots_held = band.get_slots_held()
            hotel_slots_sorted = sorted(hotel_slots_held, key=lambda x: int(x['id']))
            band_slots_sorted = sorted(band_slots_held, key=lambda x: int(x['id']))
            

            print("Slots booked by us after keeping only earliest common slots for both the hotel and the band, hotel slots: ",hotel_slots_held, ", band slots: ", band_slots_held)

            nr = 0

            #make sure we are ready to start the process, by having at most 1 booking for both the band and the hotel
            if len(hotel_slots_sorted) > 1:
                to_release = sorted(hotel_slots_sorted, key=lambda x: int(x['id']))[1]
                while(True):
                    if(hotel.release_slot(to_release['id'])):
                        break

            if len(band_slots_sorted) > 1:
                to_release = sorted(band_slots_sorted, key=lambda x: int(x['id']))[1]
                while(True):
                    if(band.release_slot(to_release['id'])):
                        break
                print("Slots booked by us after checking we keep maximumm one for botht the hotel and the band: ",hotel_slots_held, ", band slots: ", band_slots_held)

            hotel_slots_held = hotel.get_slots_held()
            band_slots_held = band.get_slots_held()


            # Check the availability of common slots
            #check if no common check_common_slots                                                                  
            common_slots = check_common_slots()
            print("First 20 common slots:")
            for i, slot in enumerate(common_slots[:20], 1):
                print(f"{i}. Slot ID: {slot['id']}")

            # Don't give up until you book the earliest common slot
            while (True):
                hotel_slot_id, band_slot_id = book_earliest_common_slot(common_slots)
                if (hotel_slot_id != None and hotel_slot_id != False):
                    break
                else:
                    common_slots = check_common_slots()

            
            hotel_slots = hotel.get_slots_held()
            band_slots = band.get_slots_held()

            #we compare the newly booked slot
            if hotel_slots and band_slots and len(band_slots)>1 and len(hotel_slots)>1:
                first_slot_hotel = sorted(hotel_slots, key=lambda x: int(x['id']))[0]
                first_slot_band = sorted(band_slots, key=lambda x: int(x['id']))[0]
                second_slot_hotel = sorted(hotel_slots, key=lambda x: int(x['id']))[1]
                second_slot_band = sorted(band_slots, key=lambda x: int(x['id']))[1]
                if (first_slot_hotel==first_slot_band and first_slot_hotel != hotel_slot_id):
                    print(f"We have already booked a better slot: {first_slot_hotel}")
                    while (True):
                        if(band.release_slot(band_slot_id)):
                            break
                    print("We release the band slot with the worse date.")
                    hotel_slots_held = hotel.get_slots_held()
                    band_slots_held = band.get_slots_held()
                    print("Hotel slots held: ", hotel_slots_held, ", band slots held: ", band_slots_held)
                    
                    while (True):
                        if (hotel.release_slot(hotel_slot_id)):
                            break
                    print("We release the hotel slot wit the worse date.")
                    hotel_slots_held = hotel.get_slots_held()
                    band_slots_held = band.get_slots_held()
                    print("Hotel slots held: ", hotel_slots_held, ", band slots held: ", band_slots_held)

                    hotel_slot_id = first_slot_hotel
                    band_slot_id = first_slot_band
                else:
                    print(f"The slot we have booked before with id: {second_slot_band} is worse so we release it.")
                    while (True):
                        if (band.release_slot(second_slot_band)):
                            break
                    while(True):
                        if(hotel.release_slot(second_slot_hotel)):
                            break
                    # Update the booked slots to use the better slot
                    hotel_slot_id = first_slot_hotel
                    band_slot_id = first_slot_band



            # Recheck for better bookings once
            print("We recheck for better common slots...")
            common_slots = check_common_slots()
            recheck_hotel, recheck_band = recheck_for_better_bookings(common_slots, hotel_slot_id, band_slot_id)

            if (recheck_hotel == hotel_slot_id and recheck_band == band_slot_id):
                print("There is no better booking date currently available, we keep only the best one.")
            else:
                print(f"Better booking date found {recheck_hotel}")
                hotel_slot_id = recheck_hotel
                band_slot_id = recheck_band

            
            #the client chooses whether they want to recheck for better slots again
            answer = input(f"The slot You have currently booked is {hotel_slot_id}. If you want the program to keep searching for better slots, press 'y'.").lower()
            if answer == 'y':
                while True:
                    print("Rechecking for better slots...")
                    time.sleep(1)
                    # Recheck for better bookings
                    common_slots = check_common_slots()
                    recheck_hotel, recheck_band = recheck_for_better_bookings(common_slots, hotel_slot_id, band_slot_id)

                    if (recheck_hotel == hotel_slot_id and recheck_band == band_slot_id):
                        answer = input(f"There is no better booking date currently available. We release the worse one nely booked. If you want the program to keep searching for better slots, press 'y'.").lower()
                        if answer != 'y':
                            #make sure we keep only one slot before exiting the program
                            nr = 0
                            for slot in hotel_slots_held:
                                if nr != 0:
                                    while(True):
                                        if(hotel.release_slot(slot['id'])):
                                            break
                                nr =+ 1

                            nr2 = 0

                            for slot in band_slots_held:
                                if nr2 != 0:
                                    while(True):
                                        if(band.release_slot(slot['id'])):
                                            break
                                nr2 =+ 1
                            print("Thanks for using our booking services, your date is ", hotel_slot_id)
                            break
                    else:
                        print(f"Better booking date found {recheck_hotel}")
                        hotel_slot_id = recheck_hotel
                        band_slot_id = recheck_band
                        answer = input(f"There is no better booking date currently available. We release the worse one nely booked. If you want the program to keep searching for better slots, press 'y'.").lower()
                        if answer != 'y':
                            #make sure we keep only one slot before exiting the program
                            nr = 0
                            for slot in hotel_slots_held:
                                if nr != 0:
                                    while (True):
                                        if (hotel.release_slot(slot['id'])):
                                            break
                                nr =+ 1

                            nr2 = 0

                            for slot in band_slots_held:
                                if nr2 != 0:
                                    while (True):
                                        if(band.release_slot(slot['id'])):
                                            break
                                nr2 =+ 1
                            print("Thanks for using our booking services, your date is ", hotel_slot_id)
                            break
                #make sure we keep only one slot before exiting the program
                nr = 0
                for slot in hotel_slots_held:
                    if nr != 0:
                        while (True):
                            if (hotel.release_slot(slot['id'])):
                                break
                    nr =+ 1

                nr2 = 0

                for slot in band_slots_held:
                    if nr2 != 0:
                        while(True):
                            if(band.release_slot(slot['id'])):
                                break
                    nr2 =+ 1
                break
            else: 
                #make sure we keep only one slot before exiting the program
                nr = 0
                for slot in hotel_slots_held:
                    if nr != 0:
                        while(True):
                            if(hotel.release_slot(slot['id'])):
                                break
                    nr =+ 1

                nr2 = 0

                for slot in band_slots_held:
                    if nr2 != 0:
                        while(True):
                            if(band.release_slot(slot['id'])):
                                break
                    nr2 =+ 1
                break

        except KeyboardInterrupt:
            print("Search for better slots stopped.")
            #make sure we keep only one slot before exiting the program
            nr = 0
            for slot in hotel_slots_held:
                if nr != 0:
                    while(True):
                        if(hotel.release_slot(slot['id'])):
                            break
                nr =+ 1

            nr2 = 0

            for slot in band_slots_held:
                if nr2 != 0:
                    while(True):
                        if(band.release_slot(slot['id'])):
                            break
                nr2 =+ 1
            print("Thanks for using our booking services, your date is ", hotel_slot_id)
            break

        except Exception as e:
            print(f"An error occurred: {e}")



if __name__ == "__main__":
    main()



