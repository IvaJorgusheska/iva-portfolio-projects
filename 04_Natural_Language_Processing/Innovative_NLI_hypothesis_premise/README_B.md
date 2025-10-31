# BiLSTM based solution for Natural Languahe Inference

This solution provides a Python notebook for training and validation of a BiLSTM model with custom heuristics for NLI. 

---

## Project Overview

All the parts are described in the form of comments in the code. However, here is a short summary of what these parts are:

### **1. Data Loading & Preprocessing**
- Load the **training (`train.csv`)** and **validation (`dev.csv`)** datasets.
- Apply **text preprocessing** steps.
-  **To run this part**, make sure that CSV file is in the same folder as your Python Notebook.

### **2. Vectorizing text and building a Model architecture**

### **3. Building & Training the BiLSTM Model**:
 - Link to the Google drive to the model is here: https://drive.google.com/drive/folders/1I2e0pzHmdWM7X2zAZSiGFmQcjIPSBaXd?usp=sharing

---

## Demo Notebook (Inference-Only)
The **demo notebook** is designed for making predictions on new datasets.

### **Key Differences from Training Notebook**
- Uses a **custom dataset class**, but with **two columns instead of three** (since labels are absent).
- File path to the file with testing data has to be passed to a function
- The final predictions are **saved to a file**, with a filename "predictions.csv".

---

## Setup & Requirements
- The notebooks were used on **Google Colab**, so all extra potential imports for other platforms are **not included**. However any addditional library you may need can be installed, and the notebooks can be run on any platform.
- Requires **Tensorflow** for training.
