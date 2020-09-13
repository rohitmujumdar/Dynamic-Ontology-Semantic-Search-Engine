# Semantic Search Engine using a Dynamic Ontology

## This project was done as a part of the Capstone (Major) B.Tech project at Vishwakarma Institute of Technology, Pune (2016-17)

### Summary

The semantic search engine needs the user to enter a query keyword, with which data, text, images and videos, relevant and suitable to a primary school student is scraped from the internet. This involves web crawling and web scraping. The text data fetched from various sources iscleaned and processed to make it suitable for Triplet extraction. The phrases (relations) from theTriplets are compared for the similarity using Phrase2Vec model, and many similar relations arereplaced by a common relation which semantically covers a majority of similar relations. This data is used to construct an ontology for the query word and is stored in an OWL-XML format. Another aim involves extending this ontology for further query searches and using the leaf nodes to perform 1- level depth offline scraping and build the ontology further. The final output involves displaying the definition text, images and videos related to the text and use the ontology to suggest similar keywords and fetch their results.
