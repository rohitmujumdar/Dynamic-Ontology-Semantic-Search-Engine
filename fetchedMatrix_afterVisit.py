import sys
from gensim.models import word2vec

#prefixPath = "../../../../../../"
prefixPath = "./"
pathToBinVectors = prefixPath+'GoogleNews-vectors-negative300.bin'

#print("Loading the data file... Please wait...")
model1 = word2vec.Word2Vec.load_word2vec_format(pathToBinVectors, binary=True)
#print("Successfully loaded 3.6 G bin file!")

# This will return NumPy vector of the word "resume"
# model1['resume']  

import numpy as np
import math
from nltk.corpus import stopwords

class PhraseVector:

    def __init__(self, phrase):
        self.vector = self.PhraseToVec(phrase)
    # <summary> Calculates similarity between two sets of vectors based on the averages of the sets.</summary>
    # <param>name = "vectorSet" description = "An array of arrays that needs to be condensed into a single array (vector). In this class, used to convert word vecs to phrases."</param>
    # <param>name = "ignore" description = "The vectors within the set that need to be ignored. If this is an empty list, nothing is ignored. In this class, this would be stop words."</param>
    # <returns> The condensed single vector that has the same dimensionality as the other vectors within the vecotSet.</returns>
    def ConvertVectorSetToVecAverageBased(self, vectorSet, ignore = []):
        if len(ignore) == 0: 
            return np.mean(vectorSet, axis = 0)
        else: 
            return np.dot(np.transpose(vectorSet),ignore)/sum(ignore)

    def PhraseToVec(self, phrase):
        cachedStopWords = stopwords.words("english")
        phrase = phrase.lower()
        wordsInPhrase = [word for word in phrase.split() if word not in cachedStopWords]
        vectorSet = []
        for aWord in wordsInPhrase:
            try:
                wordVector=model1[aWord]
                vectorSet.append(wordVector)
            except:
                pass
        return self.ConvertVectorSetToVecAverageBased(vectorSet)

    # <summary> Calculates Cosine similarity between two phrase vectors.</summary>
    # <param> name = "otherPhraseVec" description = "The other vector relative to which similarity is to be calculated."</param>
    def CosineSimilarity(self, otherPhraseVec):
        cosine_similarity = np.dot(self.vector, otherPhraseVec) / (np.linalg.norm(self.vector) * np.linalg.norm(otherPhraseVec))
        try:
            if math.isnan(cosine_similarity):
                cosine_similarity=0
        except:
            cosine_similarity=0             
        return cosine_similarity


if __name__ == "__main__":
    '''
    print( "###################################################################")
    print( "###################################################################")
    print( "########### WELCOME TO THE PHRASE SIMILARITY CALCULATOR ###########")
    print( "###################################################################")
    print( "###################################################################")
    '''
    lst = []
    d = {}

    
    for line in open(prefixPath+'output2.txt') : #./relationFile.txt
        lst.append(line.split(';')[0])

    '''
    for i in range(len(lst)-1) :
        userInput1 = lst[i]
        for j in range(i+1,len(lst)) :
            userInput2 = lst[j]

            phraseVector1 = PhraseVector(userInput1)
            phraseVector2 = PhraseVector(userInput2)
            similarityScore  = phraseVector1.CosineSimilarity(phraseVector2.vector)

            d.update({(i,j) : similarityScore})
    '''
    
    
    #lst = [ "is Inside", "is called as", "is needed For", "controls", "is A Type Of", "produces", "converted To", "caused By", "reduces", "obtained From", "consists Of" ]
    #lst = [ "is required for", "is called as", "is needed For", "is caused by", "is known as", "produces", "is converted To", "used for" ]
    
    
    fetchedRelations = lst[:]
    '''print("The fetched relations are : ")
    print(fetchedRelations)'''

    #changes
    w = len(fetchedRelations)
    #print(w)
    fetchedMatrix = [[0 for x in range(w)] for y in range(w)]

    linearFetched = [] # read it from the old_base_relations

    for i in range(0,w) :
      phraseVector1 = PhraseVector(fetchedRelations[i])
      for j in range(0,w) :
        phraseVector2 = PhraseVector(fetchedRelations[j])
        k  = phraseVector1.CosineSimilarity(phraseVector2.vector)
        fetchedMatrix[i][j] = k
        #        fetchedMatrix[j][i] = k
        linearFetched.append((fetchedRelations[i],fetchedRelations[j],k))


    for i in range(0,w) : 
      for j in range(0,w) :
          print(str(fetchedMatrix[i][j]),end=',')
      print('')
      pass
    

    i=0
    rel = []
    for ele in fetchedMatrix :
        avg = sum(ele)/len(ele)
        rel.append((i,avg))
        i+=1

    newrel = sorted(rel, key=lambda x: x[1],reverse=True)
    print(newrel)

    
    totalLst = []   
    for ele in newrel : #hard-coded
        if ele[1] < 0.2 :
            break
        newtmpAkaLst = []
        tmpAkaLst = fetchedMatrix[ele[0]]
        for i in range(0,len(tmpAkaLst)) :
            if tmpAkaLst[i] > 0.2:
                newtmpAkaLst.append( (i,tmpAkaLst[i]) )
        totalLst.append(sorted(newtmpAkaLst, key=lambda x: x[1],reverse=True))

    f = open(prefixPath+'pythonOP.txt','w+')
    for ele in totalLst :
        f.write(str(ele)+"\n")

    for i in range(len(fetchedRelations)) :
        f.write(str(i)+":"+fetchedRelations[i]+"\n")
    f.close()

    newReplacement = []
    for i in range(len(fetchedRelations)) :
        newReplacement.append(-1)

    ele = totalLst[0]
    #newReplacement[ele[0][0]] = ele[0]
    
    for i in range(0,len(ele)) :
        newReplacement[ele[i][0]] = (ele[0][0], ele[i][1]) 

    print("****new replaement\n")#na
    print(newReplacement)#na
    print("===len totallst\n")#na
    #print(len(totalLst))#na
    print(totalLst)#na

    visited = []
    for i in range(len(newrel)) :
        visited.append(False)
    visited[totalLst[0][0][0]] = True

    
    for i in range(1,len(totalLst)) :
        ele = totalLst[i]
        mytemp = ele[0][0]
        print("***\n")#na
        print(ele)#na
        for j in range(1,len(ele)) :
            if newReplacement[ele[j][0]] == -1 :
                newReplacement[ele[j][0]] = (totalLst[i][0][0], ele[j][1])
                print('Control was here inside IF')
            else :
                if newReplacement[ele[j][0]][1] < ele[j][1] and not visited[ele[j][0]] :
                    newReplacement[ele[j][0]] = (totalLst[i][0][0], ele[j][1])
                    #print('Control was here inside ELSE K ANDAR IF')
                #print('Control was here inside ELSE')
            print("NR:"+str(newReplacement))
        visited[mytemp] = True

    print('Visited ')
    print(visited)

    
    f = open(prefixPath+'NewOutputFileWithReplaced_BASE_Relations.txt', 'w+')
    for i in range(len(newReplacement)) :
        if( type(newReplacement[i]) == type( (1,2,3) ) ) :
            f.write(fetchedRelations[newReplacement[i][0]]+"\n")
        else :
            f.write(lst[i]+"\n")
    f.close()
    
    #sys.exit(0)
    # ==================
    plst = []
    for line in open('NewOutputFileWithReplaced_BASE_Relations.txt') :
        plst.append(line[:-1])

    ReqdTuples = []
    i=0
    for line in open('output2.txt') : #output3.txt
        x = line.split(';')
        print(line)
        print((plst[i], x[1], x[2].replace("\n","")))
        ReqdTuples.append( (plst[i], x[1], x[2].replace("\n","")) )
        i+=1

    f = open('NounRelationNoun.txt', 'w+')
    for ele in ReqdTuples:
        f.write(ele[0]+";"+ele[1]+";"+ele[2]+"\n")
    f.close()

    print('At the end')
    # save base relation and extend
