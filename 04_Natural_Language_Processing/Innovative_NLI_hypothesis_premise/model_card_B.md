---
{}
---
language: en
license: cc-by-4.0
tags:
- text-classification
repo: https://github.com/Shrillade/NLU_model_card

---

# Model Card for m00896yv-c00714ij-NLI

<!-- Provide a quick summary of what the model is/does. -->

This is a classification model that is supposed to solve NLI problem,
    that is, tell if a provided hypothesis entails or contradicts the premise.


## Model Details

### Model Description

<!-- Provide a longer summary of what this model is. -->


    The proposed model was inspired by the *Wang et al.* (2019) which proposed an architecture based on **BiLSTMs** to solve a **Natural Language
    Inference** task. We combine **BiLSTMs** with custom feature representation in order to enhance the performance of the model and
    make it better in generalization. Let's now briefly discuss solutions that were designed.
    
    ## Preprocessing
    In the considered case, text preprocessing was quite straightforward, because after analysis of the provided training data it was clear that
    most structures in it are common and usually accounted for in basic preprocessing techniques. Hence, we have normalized punctuation without deleting it,
    because in case NLI punctuation might be useful to infer meanings and relation. We have also normalized spaces and remove common contractions to preserve
    sense of words used in those.
    
    ## Vectorization
    In order to pass the text to the model it had to be vectorized. Therefore we have used a TextVectorization layer provided by Tensorflow
    in order to transform text into vectors. It also has to be admitted that provided text data had short sentences, therefore the assumption was made that
    the model has to work with short "everyday conversation" sentences, therefore vector size was capped at 25 tokens.
    
    ## Architecture
    Firstly, word embedding were trained together with the model using vectors and vocabulary provided by TextVectorizer. Then learnt embeddings were passed to two stacked
    BiLSTM layers 300 units each. Then output vectors were pooled using max pooling. It is important to note that premise and hypothesis sentences were
    encoded independently. Then produced vectors were used to get absolute difference between them and element-wise product. Then all 4 vectors, that is, 
    original two vectors, representing sentences, and two produced vectors, representing difference between sentences, were concatenated and passed into
    MLP with a hidden layer of 512 units.
    
    ## Evaluation
    For evaluation a separate dataset was used and ADAm optimizer was chosen as a optimizer for the model.

- **Developed by:** Yevhenii Voropaiev and Iva Jorgusheska
- **Language(s):** English
- **Model type:** Supervised
- **Model architecture:** BiLSTM, as described in the description of the model.
- **Finetuned from model [optional]:** BiLSTM

### Model Resources

<!-- Provide links where applicable. -->

- **Repository:** [More information needed]
- **Paper or documentation:** https://arxiv.org/pdf/1804.07461

## Training Details

### Training Data

<!-- This is a short stub of information on the training data that was used, and documentation related to data pre-processing or additional filtering (if applicable). -->

24k premise-hypothesis pair provided by university.

### Training Procedure

<!-- This relates heavily to the Technical Specifications. Content here should link to that section when it is relevant to the training procedure. -->

#### Training Hyperparameters

<!-- This is a summary of the values of hyperparameters used in training the model. -->


      - train_batch_size: 128
      - eval_batch_size: 32
      - seed: 42
      - num_epochs: 2

#### Speeds, Sizes, Times

<!-- This section provides information about how roughly how long it takes to train the model and the size of the resulting model. -->


      - overall training time: 2 mins
      - duration per training epoch: 1 min
      - model size: 127MB

## Evaluation

<!-- This section describes the evaluation protocols and provides the results. -->

### Testing Data & Metrics

#### Testing Data

<!-- This should describe any evaluation data used (e.g., the development/validation set provided). -->

A set of as little as 50 sentence pairs was provided due to the nature of the coursework. However, development dataset was also
    used to assess model's performance, results are listed below.

#### Metrics

<!-- These are the evaluation metrics being used. -->


      - F1-score
      - Accuracy

### Results

The model obtained an F1-score of 80% and an accuracy of 80% for the trial dataset. And F1-score of 68% and accuracy of 67% for the development dataset.

## Technical Specifications

### Hardware


      - RAM: at least 16 GB
      - Storage: at least 2GB,
      - GPU: T4

### Software


      - Tensorflow 2.18.0
      - Pandas 2.2.2
      - Scikit-learn 1.5.2
      

## Bias, Risks, and Limitations

<!-- This section is meant to convey both technical and sociotechnical limitations. -->


    ### Bias Considerations
  - **Dataset Imbalances:** The dataset may not represent all demographic groups equally, potentially leading to biased predictions.
  - **Domain Specificity:** The model is trained on a specific dataset and may not generalize well to texts from different domains (e.g., legal, medical).
  - **Mitigation Strategies:** Regular audits and fine-tuning on diverse datasets are recommended to minimize bias.
  - **Transformers Superiority:** BiLSTMs tend to perform worse than transfromers in NLU tasks due to transformers superior architecture

  ### Risks & Ethical Concerns
  - **Misclassification Risks:** Errors in NLI classification could mislead users in critical applications, such as automated fact-checking.
  - **Adversarial Robustness:** The model may be vulnerable to adversarial attacks, where minor input modifications change predictions.
  
  ## Limitations
- **Language Scope:** The model is trained only on English data and cannot generalize to other languages.
- **Computational Constraints:** Due to GPU limitations, we could not afford to train models with the big number of parameters which has affected the performance due to the fact
that models with more parameters tend to perform better, especially in NLI tasks.

## Additional Information

<!-- Any other information that would be useful for other people to know. -->

The hyperparameters were determined by experimentation
      with different values.
