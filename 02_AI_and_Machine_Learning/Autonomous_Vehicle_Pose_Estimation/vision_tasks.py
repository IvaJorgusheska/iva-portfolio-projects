#!/usr/bin/env python3
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
"""Sample code for Comp24011 SLAM lab solution

NB: The default code in non-functional; it simply avoids type errors
"""

__author__ = "c00714ij"
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

import cv2
import sys
import numpy as np

from vision_tasks_base import VisionTasksBase

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

class VisionTasks(VisionTasksBase):
    def __init__(self, *params):
        """Initialise instance by passing arguments to super class"""
        super().__init__(*params)
        

    """Implements feature matching based on distance thresholding

        :param des1: descriptors for the previous image (query)
        :type des1:  list
        :param des2: descriptors for the current image (train)
        :type des2:  list
        :param threshold: threshold value
        :type threshold:  float

        :return: matches for descriptors
        :rtype:  list
        """    
    def dt(self, des1, des2, threshold):
        bf = cv2.BFMatcher()
        golemina  = len(des1)
        matches = bf.knnMatch(des1, des2, k =golemina + 1)

        matches_list = []

        for match in matches:
            single_match = []
            for m in match:
                if m.distance < threshold:
                    single_match.append(m)
                    #else:
                    #    single_match.append([])
            matches_list.append(single_match)
        return matches_list

        


    def nn(self, des1, des2, threshold=None):
        """Implements feature matching based on nearest neighbour

        :param des1: descriptors for the previous image (query)
        :type des1:  list
        :param des2: descriptors for the current image (train)
        :type des2:  list
        :param threshold: threshold value
        :type threshold:  float or None

        :return: matches for descriptors
        :rtype:  list
        """
        bf = cv2.BFMatcher()
        matches = bf.knnMatch(des1, des2, k = 1)

        matches_list = []

        for match in matches:
            single_match = []
            for m in match:
                distance = m.distance
                if threshold is None or distance < threshold:
                    single_match.append(m)
            matches_list.append(single_match)        
        return matches_list




    def nndr(self, des1, des2, threshold):
        """Implements feature matching based on nearest neighbour distance ratio

        :param des1: descriptors for the previous image (query)
        :type des1:  list
        :param des2: descriptors for the current image (train)
        :type des2:  list
        :param threshold: threshold value
        :type threshold:  float

        :return: matches for descriptors
        :rtype:  list
        """
        bf = cv2.BFMatcher()
        matches = bf.knnMatch(des1, des2, k = 2)

        if len(matches) < 2 or not matches[0] or not matches[1]:
            return []
        matches_list = []
        
        distance1 = 0
        distance2 = 0
        turn = 1
        for match in matches:
            if match and match[0] and match[1]:
                single_match = []
                distance1 = match[0].distance
                distance2 = match[1].distance
                if distance2 != 0:
                    ratio = distance1 / distance2
                else:
                    ratio = 2            
                if ratio < threshold:
                    for m in match:
                        single_match.append(m)
                        break
                #else:
                #    single_match.append([])   
                matches_list.append(single_match)

        return matches_list
        
    
    def matching_info(self, kp1, kp2, feature_matches):
        """Collects information about the matches of some feature

        :param kp1: keypoints for the previous image (query)
        :type kp1:  list
        :param kp2: keypoints for the current image (train)
        :type kp2:  list
        :param feature_matches: matches for the feature
        :type feature_matches:  list

        :return: coordinate of feature in previous image,
                 coordinates for feature matches in current image,
                 distances for feature matches in current image
        :rtype:  tuple, list, list
        """
        
        if not kp1 or not kp2 or not feature_matches or not any(feature_matches):
            return (0, 0), [], []

        matches_list = []
        distances = []
        #
        
            # Check if it's a non-empty list of matches
            
        for m in feature_matches:
            dist_value = m.distance
                
                # Integer indexes
            query_kp_index = m.queryIdx
            ref_kp_index = m.trainIdx

                # Actual keypoints using the indexes
            ref_kp = kp2[ref_kp_index]

                # Keypoints have attributes pt, x and y coordinates
            x_coord_ref_kp = int(ref_kp.pt[0])
            y_coord_ref_kp = int(ref_kp.pt[1])

            matching_keypoint = (x_coord_ref_kp, y_coord_ref_kp)

            matches_list.append(matching_keypoint)
            distances.append(dist_value)

            # Extract the coordinates of the first query keypoint
            x_coord_query_kp = int(kp1[feature_matches[0].queryIdx].pt[0])
            y_coord_query_kp = int(kp1[feature_matches[0].queryIdx].pt[1])
            '''
        else:
            # It's a single DMatch object
            dist_value = feature_matches.distance
            
            # Integer indexes
            query_kp_index = feature_matches.queryIdx

            # Actual keypoints using the index
            x_coord_query_kp = int(kp1[query_kp_index].pt[0])
            y_coord_query_kp = int(kp1[query_kp_index].pt[1])

            # Add the single match to the lists
            matching_keypoint = (x_coord_query_kp, y_coord_query_kp)
            matches_list.append(matching_keypoint)
            distances.append(dist_value)
        '''
        return (x_coord_query_kp, y_coord_query_kp), matches_list, distances
        



#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if __name__ == '__main__':
    import run_odometry
    run_odometry.main(sys.argv[1:])

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# vim:set et sw=4 ts=4:
