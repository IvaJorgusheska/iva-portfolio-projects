# Transformer-Based Meta-Learning for Text Classification

This transformer based solution provides **two Python notebooks** designed for training and testing a **meta-learning model** that improves classification accuracy by combining predictions from multiple fine-tuned transformers.

---

## Project Overview

The main solution notebook consists of **three key parts**:

### **1. Data Loading & Preprocessing**
- Load the **training (`train.csv`)** and **validation (`dev.csv`)** datasets.
- Apply **text preprocessing** steps.
-  **To run this part**, upload the CSV files to Colab or modify the file paths if using different datasets.

### **2. Fine-Tuning Three Transformer Models**
- Fine-tune **BERT, RoBERTa, and ALBERT** for text classification.
- **Model Checkpointing:** 
  - Once trained, the models are **saved to Google Drive**.
  - **After training once, you don’t need to retrain**—simply load the saved models instead (as indicated in the code comments).
- **Colab-Specific Behavior:** 
  - The training code connects to **Google Drive**, whereas in the demo notebook, the models are loaded **via direct links** instead.

### **3. Building & Training the Meta-Learner**
- For a **new dataset**, get **probability predictions** from the fine-tuned transformers (using softmax).
- Build a **Meta-Learner** (a simple yet effective neural network).
- Train the Meta-Learner using the **stacked transformer predictions as input**.
- Evaluate performance, showing improved accuracy over individual transformers.

---


### Links to the saved models
- Albert: https://drive.google.com/file/d/1-WoL-zxDotkggGn3kwsJcSWrWV3s1YWh/view?usp=sharing
- Bert: https://drive.google.com/file/d/1--g4wl1gyOWCEHjfhzk1dIHVwF40YI4A/view?usp=sharing
- RoBerta: https://drive.google.com/file/d/1-9vsFS7GrXtF-3aKZ3D3fnSOXUUy8LGS/view?usp=sharing
- Meta-learner: https://drive.google.com/file/d/1Xw1U7s7LB0fgGZy8l6_JZvcdXp2o7mx8/view?usp=sharing

### Sizes of the saved models:
-  Albert: 44.6 MB
-  Bert: 417.7 MB
-  RoBerta: 475.6 MB

Size of the metalearner: 39 KB

Since the sizes of the models are big, they are all saved on the cloud and you can access them with the provided links. 

## Demo Notebook (Inference-Only)
The **demo notebook** is designed for making predictions on new datasets.

### **Key Differences from Training Notebook**
- Uses a **custom dataset class**, but with **two columns instead of three** (since labels are absent).
- The fine-tuned transformers are **loaded from links**, not from Google Drive.
- The Meta-Learner is also **loaded from a saved link**.
- The final predictions are **saved to a file**, with a customizable filename.

### **Steps to Run the Demo**
1. Specify the **file name or path** of the dataset you want predictions for.
2. Load the **fine-tuned transformers** using saved model links.
3. Preprocess the dataset and create a **custom dataset loader**.
4. Generate **predictions (probabilities) from all transformers**.
5. Load the **Meta-Learner**, pass in the stacked probabilities, and get final predictions.
6. Save the results to a file (you can modify the filename in the last function).

---

## Setup & Requirements
- The notebooks were used on **Google Colab**, so all extra potential imports for other platforms are **not included**. However any addditional library you may need can be installed, and the notebooks can be run on any platform.
- Requires **PyTorch**, **Transformers (Hugging Face)**, and **Google Drive access** for training.

---


## Notes & Recommendations
- **For training**, use the **main notebook** (with Drive connection).
- **For inference**, use the **demo notebook** (with model links).
- The **Meta-Learner effectively improves classification accuracy** beyond any single transformer.
- Modify file paths and filenames as needed before running.
