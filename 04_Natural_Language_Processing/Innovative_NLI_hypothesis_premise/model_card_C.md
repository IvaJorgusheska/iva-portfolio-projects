# Transformer-Based Meta-Learning for Text Classification: Model Card

---

# Model Card for m00896yv-c00714ij-NLI, Transformer Based Approach

<!-- Provide a quick summary of what the model is/does. -->

## Overview (Model summary)
Transformers have significantly enhanced the performance of deep learning models in natural language processing (NLP). Compared to traditional machine learning techniques, they allow models to achieve superior results using the same amount of data. However, transformers exhibit certain limitations, particularly when data availability is highly constrained. A literature review on Natural Language Inference (NLI) tasks—where models determine the relationship between a hypothesis and a premise—revealed that the choice of the transformer model is crucial for optimal performance. Different transformers focus on distinct aspects of the sequence, especially in low-data scenarios.

Inspired by recent advancements in ensemble learning, particularly the 2024 paper *"Natural Language Inference with Transformer Ensembles and Explainability Techniques"*, we propose a meta-learning approach. This approach aggregates predictions from multiple transformers and trains a meta-learner to produce a final classification of entailment or contradiction for hypothesis-premise pair.

Link to the mentioned paper: https://www.mdpi.com/2079-9292/13/19/3876 

---

## Model Details
- **Developed by:** Iva Jorgusheska and Yevhenii Voropaiev
- **Language(s):** English
- **Model type:** Transformer Based
- **Model architecture:** MLP on top of 3 finetuned transformers

### Base Models
We utilize three transformer models: **BERT**, **ALBERT**, and **RoBERTa**. Each model contributes unique representational power:  
- **BERT**: Uses bidirectional self-attention and a deep encoder stack, excelling in general language understanding.  
- **ALBERT**: A lightweight alternative with parameter-sharing techniques, reducing redundancy and memory usage.  
- **RoBERTa**: An optimized BERT variant with dynamic masking and larger batch training, capturing contextual dependencies more effectively.  
Among these, **ALBERT** is the most lightweight due to its parameter-sharing mechanism. 

### Meta-Learner Architecture  
The meta-learner is a neural network that takes the output probabilities from the three transformer models as input and predicts the final classification outcome.
The MetaLearner is a lightweight, fully connected neural network designed for classification tasks. It takes a vector of input features—such as stacked probabilities from base models—and processes them through two hidden layers with ReLU activations and dropout regularization, before outputting the final class logits. This architecture balances model capacity and generalization, making it suitable for low-data meta-learning scenarios.



Each transformer's logit outputs are converted to probabilities and stacked to form the input for the meta-learner. A deeper neural network was deemed unnecessary due to the limited data and the effectiveness of a simple feedforward model in capturing the multidimensional patterns. Initial experiments with logistic regression yielded poor results (0.5 accuracy), likely due to its limited capacity to capture non-linear interactions among transformer outputs.

## Data Usage and Training Strategy
To prevent data leakage between the transformers and the meta-learner, different datasets were used:

- **Transformers**: Trained on the full training dataset provided by the university, 24432 labeled hypothesis premise pairs.

- **Meta-Learner**: Trained on the full development dataset provided by the university, 6736 labeled hypothesis premise pairs.

This ensures that the meta-learner operates in conditions resembling real-world deployment, where it encounters transformer-generated predictions on unseen data. During development, various data splits were explored to validate the architecture, before final retraining.


## Preprocessing

Preprocessing ensures high-quality input data:
- Hypothesis and premise are correctly parsed, handling commas inside quoted text.
- Removal of extraneous spaces and quotation marks.
- Expansion of common abbreviations.
- Tokenization using model-specific tokenizers.

A custom dataset class was implemented for handling NLI data.

## Training Details
Data loaders were used for all three transformers with batch size tuning. 
Grid search experiments determined that a batch size of 64 provided optimal accuracy, as smaller batch sizes led to overfitting and generalization difficulties.

**Loss Functions and Learning Rate Scheduling**
- **ALBERT:** Trained with focal loss to handle class imbalances.
- **BERT & RoBERTa:** Trained with cross-entropy loss.
- **Learning Rate Scheduler:** Optimized via linear scheduling:

#### Training Hyperparameters
For the transformers finetuning: 
- Batch size: 64
- Epochs: 3
- Focal loss for Albert, cross-entropy for Bert and Roberta
- Maximum length of sentence: 124
- Learning Rate scheduler
- Optimizer: Adam

    
For the meta-learner:
- Batch size for the loader: 32
- Dropout rate: 0.2
- Epochs: 10, more epochs did not lead to significant improvement

### Speed and Size

It takes around 3 hours to fine-tune the transformers and 5 minutes to train the meta-learner. 
The finetuned models have sizes:

-  Albert: 44.6 MB
-  Bert: 417.7 MB
-  RoBerta: 475.6 MB

Size of the metalearner: 39 KB

### Links to the saved models
- Albert: https://drive.google.com/file/d/1-WoL-zxDotkggGn3kwsJcSWrWV3s1YWh/view?usp=sharing
- Bert: https://drive.google.com/file/d/1--g4wl1gyOWCEHjfhzk1dIHVwF40YI4A/view?usp=sharing
- RoBerta: https://drive.google.com/file/d/1-9vsFS7GrXtF-3aKZ3D3fnSOXUUy8LGS/view?usp=sharing
- Meta-learner: https://drive.google.com/file/d/1Xw1U7s7LB0fgGZy8l6_JZvcdXp2o7mx8/view?usp=sharing


### Metrics and Results

The model performance was evaluated using Accuracy and F1 Score.

| Model         | Accuracy | F1 Score |
|--------------|----------|----------|
| BERT         | 0.793    | 0.791    |
| RoBERTa      | 0.824    | 0.830    |
| Albert       | 0.842    | 0.841    |
| Meta-Learner | 0.891    | 0.893    |

The meta-learner outperformed individual transformers, demonstrating the effectiveness of stacking predictions.

### Hardware
- RAM: at least 16 GB
- Storage: at least 2GB,
- GPU: T4

### Software
- Torch version: 2.6.0
- Cuda: 12.4
- Transformer: 4.50.3 or 4.37.2
      


## Bias, Risks, and Limitations
### Bias Considerations
- **Dataset Imbalances:** The dataset may not represent all demographic groups equally, potentially leading to biased predictions.
- **Domain Specificity:** The model is trained on a specific dataset and may not generalize well to texts from different domains (e.g., legal, medical).
- **Mitigation Strategies:** Regular audits and fine-tuning on diverse datasets are recommended to minimize bias.

### Risks & Ethical Concerns
- **Misclassification Risks:** Errors in NLI classification could mislead users in critical applications, such as automated fact-checking.
- **Adversarial Robustness:** The model may be vulnerable to adversarial attacks, where minor input modifications change predictions.

## Limitations
- **Language Scope:** The model is trained only on English data and cannot generalize to other languages.
- **Computational Constraints:** Due to GPU limitations, we could not incorporate DeBERTa, which is known for its strong NLI performance. Future iterations should include this model to enhance the ensemble’s effectiveness.

















