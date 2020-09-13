import sys
import subprocess
import webbrowser

url = 'localhost/myindex.php'
pth = "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"
webbrowser.register('chrome', None, webbrowser.BackgroundBrowser(pth))
chrome = webbrowser.get('chrome')
# chrome.open_new_tab('chrome://newtab')

webbrowser.get("C:/Program Files (x86)/Google/Chrome/Application/chrome.exe %s").open(url)

from time import sleep
#sleep(3) # 50 ms
import os.path

query = "" # str(sys.argv[1])
print(1)
while not os.path.isfile("QueryString.txt") :
    pass
print(1)

for line in open('QueryString.txt'):
    query = line.replace("\n","")
    break


print(1)
os.remove("QueryString.txt")
thatFile = 'ontology_def.txt'
#query = str(sys.argv[1])
output = " "
print(1)
subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.DriverProgram', query])
import os.path
import os
if( os.path.isfile("indicator.txt") ) :
    subprocess.call(['java', '-cp', 'TextScrap-jar-with-dependencies.jar', 'com.TripletExtraction.TextScrap', query])
    subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.PairPatternMatrix'])
    if( os.path.isfile("indicator2.txt") ) :
        output = "No Definition Found"
        os.remove("indicator2.txt")
    else :
        subprocess.call(['python', 'fetchedMatrix_afterVisit.py'])
        subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.buildFile'])

    os.remove("indicator.txt")
    # ab toh rahega hi 
    subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.DriverProgram', query])


# for debug purpose below code must be commented.
# output2.txt always appends hence
if( os.path.isfile("output2.txt") ) :
    os.remove('output2.txt')

url = 'localhost/myui.php?q='+query
chrome.open_new_tab(url)
# if thatFile exists, remove it, okay
