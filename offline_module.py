
lst = ['legs','wings','fins']
#lst = ['legs','wings','fins','root','stem','leaf','fruit','flower','fenugreek','onion greens','coriander greens','radishes','fragrance', 'shelters','eggs','nests','ants','anthills','grain of soil','elephants','deer','grass','vegetation','tigers','lions','cave', 'hollows','crabs','scorpions ,wood','stones','monkey','light','straw','earth','cow dung','huts','earthen','tiles','metal', 'cement','plastic','rain','moisture','insects','termites','platforms','eco-friendly','igloos','monuments','forts','fibers', 'cotton','banana','coconut','hemp','jute','agave','coarse','woolen','silk','silkworms','sheen','artificial fiber','dust', 'sweat','soap','ventilation','morsels','forelegs','pods','jaws','fodder','proboscis','energy','staple','cereals','starch', 'carbohydrates','hair','nails','eyes','ears','nose','tongue','sensory organs','incense','taste','sour','salty','bitter','sweet', 'chilies','skin','contact','antiseptic','ointment','wound','bones','framework','skeleton ,natural','resources','water','land', 'air (pg 93)','disaster','floods','storms','earthquakes','droughts','scarcity','water','food grains','lightning','upheavals', 'cracks','solid','liquid','gas','states of matter','cleanliness','plant', 'fertilizer','soil','resistance','health','food','cereals','pulses','vegetables','vitamins','fruits','water','air','seeds','sprouts', 'temperature','mixture','preservation','rocks','earth','filtration','impurities','alum','boiling','chemicals','germs','humanbody','bones', 'joints','muscles','brain','blood','movements','hinge joint','ball and socket joint','shoulder','gliding joint','pivot joint', 'volutary muscles','size','breathing','heart','sneezing','digestion','hygiene','mouth','teeth','hair','diseases','garbage','biogas', 'animals','weather','warmth','climate','natural resources','land','vaporization','heats','clouds','substances','gas','solid form', 'liquid form','gaseous form','light','materials','dissolution','solubility','organs','stomach','lungs','chest','inhalation','exhalation', 'respiration','alimentary canal','coordination','agriculture method','crop','methods of irrigation','grain','clothes','shelter', 'basic necessities','cotton','silk','wool','rain'',''sun','climate','disease','handlooms','weaving','looms','jut','sunlight','forests', 'medicines','air','minerals','metals','fuel','living','growth','reproduction','movement','eyes','head','ears','procreation','legs','thumb', 'respiration','exhale']
import subprocess
import os
for ele in lst :
    query = ele
    subprocess.call(['java', '-cp', 'TextScrap-jar-with-dependencies.jar', 'com.TripletExtraction.TextScrap', query])
    subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.PairPatternMatrix'])
    if( os.path.isfile("indicator2.txt") ) :
        output = "No Definition Found"
        print(query +' not added')
        os.remove("indicator2.txt")
    else :
        subprocess.call(['python', 'fetchedMatrix_afterVisit.py'])
        subprocess.call(['java', '-cp', 'RAPP-jar-with-dependencies.jar', 'com.TripletExtraction.buildFile'])
    
# ask Chandolikar ma'am --> ask all the report
