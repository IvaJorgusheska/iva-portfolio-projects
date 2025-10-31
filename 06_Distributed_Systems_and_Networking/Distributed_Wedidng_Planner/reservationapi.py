""" Reservation API wrapper

This class implements a simple wrapper around the reservation API. It
provides automatic retries for server-side errors, delays to prevent
server overloading, and produces sensible exceptions for the different
types of client-side error that can be encountered.
"""

# This file contains areas that need to be filled in with your own
# implementation code. They are marked with "Your code goes here".
# Comments are included to provide hints about what you should do.

import requests
import simplejson
import warnings
import time
from typing import Union, Optional


from requests.exceptions import HTTPError
from exceptions import (
    BadRequestError, InvalidTokenError, BadSlotError, NotProcessedError,
    SlotUnavailableError,ReservationLimitError)

class ReservationApi:
    def __init__(self, base_url: str, token: str, retries: int, delay: float):
        """ Create a new ReservationApi to communicate with a reservation
        server.

        Args:
            base_url: The URL of the reservation API to communicate with.
            token: The user's API token obtained from the control panel.
            retries: The maximum number of attempts to make for each request.
            delay: A delay to apply to each request to prevent server overload.
        """
        self.base_url = base_url
        self.token    = token
        self.retries  = retries
        self.delay    = delay

    def _reason(self, req: requests.Response) -> str:
        """Obtain the reason associated with a response"""
        reason = ''

        # Try to get the JSON content, if possible, as that may contain a
        # more useful message than the status line reason
        try:
            json = req.json()
            reason = json['message']

        # A problem occurred while parsing the body - possibly no message
        # in the body (which can happen if the API really does 500,
        # rather than generating a "fake" 500), so fall back on the HTTP
        # status line reason
        except simplejson.errors.JSONDecodeError:
            if isinstance(req.reason, bytes):
                try:
                    reason = req.reason.decode('utf-8')
                except UnicodeDecodeError:
                    reason = req.reason.decode('iso-8859-1')
            else:
                reason = req.reason

        return reason


    def _headers(self) -> dict:
        """Create the authorization token header needed for API requests"""
        # Your code goes here
        return {'Authorization': f'Bearer {self.token}'}

        #67639ebc4672fcf7cf23aa6d7b2949e368e38d25a59bd75406a2a9cb791826ac


    def _send_request(self, method: str, endpoint: str) -> requests.Response:
        """Send a request to the reservation API and convert errors to
           appropriate exceptions"""
        # Your code goes here

        # Allow for multiple retries if needed
            # Perform the request.

            # Delay before processing the response to avoid swamping server.

            # 200 response indicates all is well - send back the json data.

            # 5xx responses indicate a server-side error, show a warning
            # (including the try number).

            # 400 errors are client problems that are meaningful, so convert
            # them to separate exceptions that can be caught and handled by
            # the caller.

            # Anything else is unexpected and may need to kill the client.

        # Get here and retries have been exhausted, throw an appropriate
        # exception.
        for _ in range(self.retries):
            try:
                # Perform the request
                response = requests.request(method, self.base_url + endpoint, headers=self._headers())

                # Delay before processing the response to avoid swamping server
                time.sleep(self.delay + 0.5)

                # 200 response indicates all is well - send back the json data
                if response.status_code == 200:
                    return response.json()
                elif response.status_code >= 500 and response.status_code < 600:
                    error_message = f'Service-side error - {response.status_code}'
                    warnings.warn(error_message)
                elif response.status_code == 400:
                    raise BadRequestError(self._reason(response))
                elif response.status_code == 401:
                    raise InvalidTokenError(self._reason(response))
                elif response.status_code == 403:
                    raise BadSlotError(self._reason(response))
                elif response.status_code == 404:
                    raise SlotUnavailableError(self._reason(response))
                elif response.status_code == 409:
                    raise NotProcessedError(self._reason(response))
                elif response.status_code == 451:
                    raise ReservationLimitError(self._reason(response))

                # Anything else is unexpected and may need to kill the client
                else:
                    # error_message = f'Unexpected status code - {response.status_code}'
                    # if response.content:
                    #     error_message += f'\nResponse content: {response.content.decode("utf-8")}'
                    raise HTTPError(error_message)

            except requests.RequestException as e:
                # Handle connection errors or timeouts
                warnings.warn(f'Request failed: {e}')

        # Get here and retries have been exhausted, throw an appropriate
        # exception
        raise TimeoutError('Maximum retries exceeded')


    def get_slots_available(self) -> Union[requests.Response, None]:
        """Obtain the list of slots currently available in the system"""
        # Your code goes here
        try:
            response = self._send_request('GET', '/reservation/available')
            return response        
        except Exception as e:
            print(f"Error occurred while finding free slots: {e}")
            return None

        

    def get_slots_held(self) -> Union[requests.Response, None]:
        """Obtain the list of slots currently held by us"""
        # Your code goes here
        try:
            response = self._send_request('GET', '/reservation')
            return response            
        except Exception as e:
            print(f"Error occurred while finding the slots you hold: {e}")
            return None


    def release_slot(self, slot_id) -> bool:
        """Release a slot currently held by the client"""
        # Your code goes here
        try:
            endpoint = f'/reservation/{slot_id}'
            response = self._send_request('DELETE', endpoint)
            return True
            if response.status_code == 200:
                return True
            # elif response.status_code == 403:
            #     raise BadSlotError(self._reason(response))
            # elif response.status_code == 401:
            #     raise InvalidTokenError(self._reason(response))
            # elif response.status_code == 409:
            #     raise NotProcessedError(self._reason(response))
            # else:
            #     raise HTTPError(f'Unexpected status code - {response.status_code}')

        except Exception as e:
            print(f"Error occurred while releasing the slot: {e}")
            return False

        

    def reserve_slot(self, slot_id)->bool:
        """Attempt to reserve a slot for the client"""
        # Your code goes here
        try:
            endpoint = f'/reservation/{slot_id}'
            response = self._send_request('POST', endpoint)
            if isinstance(response, dict):
                return response.get('id') 
            # if response:
            #     return True
            #     return response.json()['id']
            elif response.status_code == 403:
                raise BadSlotError(self._reason(response))
            elif response.status_code == 401:
                raise InvalidTokenError(self._reason(response))
            elif response.status_code == 409:
                raise NotProcessedError(self._reason(response))
            elif response.status_code == 451:
                raise ReservationLimitError(self._reason(response))
            else:
                raise HTTPError(f'Unexpected status code - {response.status_code}')

        except Exception as e:
            print(f"Error occurred while reserving the slot: {e}")
            return False


