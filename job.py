#!/usr/bin/python
# -*- coding: utf-8 -*-
from random import random
import sys
import json
import os
import time

import joblib

import re
#import time

import numpy as np
import pandas as pd
##import random

import matplotlib.pyplot as plt



from scipy.sparse import hstack
import nltk
from nltk.corpus import stopwords as nltk_stopwords
from nltk.stem import WordNetLemmatizer

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.feature_extraction.text import CountVectorizer
##import gensim
##import gensim.downloader as gensim_api
##from gensim.models import Word2Vec

from sklearn.model_selection import train_test_split
from sklearn.model_selection import cross_validate

from sklearn.linear_model import LogisticRegression
from sklearn.ensemble import RandomForestClassifier

from sklearn.metrics import make_scorer
from sklearn.metrics import recall_score
from sklearn.metrics import precision_score
from sklearn.metrics import f1_score
from sklearn.metrics import roc_auc_score
from sklearn.metrics import precision_recall_curve
from sklearn.metrics import confusion_matrix
from sklearn.metrics import balanced_accuracy_score


##print 'Number of arguments:', len(sys.argv), 'arguments.'
##print 'Argument List:', str(sys.argv)

if (len(sys.argv)!=2):
    print("Не задано параметра - номера JSON!")
else:
    print("ID:"+str(sys.argv[1]))
    fileid=sys.argv[1]
    filename="json/"+str(sys.argv[1])+".json"
    print(filename)
    with open(filename) as json_file:
        data = json.load(json_file)
        print('date: ' + data['date'])

    
    
    # убираем flag_to_process и ставим  flag_run_calc
    flag_to_process_fn="flag_to_process/"+fileid
    flag_run_calc_fn="flag_run_calc/"+fileid
    flag_flag_finished_fn="flag_finished/"+fileid
    
    if os.path.exists(flag_to_process_fn):
        os.remove(flag_to_process_fn)
    else:
        print("The file "+flag_to_process_fn+" does not exist")
    if not os.path.exists(flag_run_calc_fn):
       os.mknod(flag_run_calc_fn)
    
    
    model=joblib.load('model.sav')
    
    sex=data['sex']
    weight=data['weight']
    height=data['height']
    ad_s=data['ad_s']
    ad_d=data['ad_d']
    diabet2=data['diabet2']
    sam_prishel=data['sam_prishel']
    bmi=data['bmi']
    n_fields=data['n_fields']
    age_parsing=data['age_parsing']
    point=data['point']
    tf_idf_feature=data['tf_idf_feature']
    patient_status=data['patient_status']
    
    # пока сделано так, не разобрал переменную ICD_letter
    vp=[[sex,weight,height,ad_s,ad_d,diabet2,sam_prishel,sam_prishel,bmi,n_fields,n_fields,age_parsing,point,tf_idf_feature,patient_status,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0]]

    #model.feature_names_in_
    
    res_value=str(model.predict(vp))
    #print(model.predict(vp))
    #print(model.feature_names_in_)
# дальше идут вычисления...
    #time.sleep(1) 

# всё успешно
# убираем flag_run_calc и ставим  flag_flag_finished
    if os.path.exists(flag_run_calc_fn):
       os.remove(flag_run_calc_fn)
    else:
       print("The file "+flag_run_calc_fn+" does not exist")
    if not os.path.exists(flag_flag_finished_fn):
       os.mknod(flag_flag_finished_fn)

    #res_value=random()
#    print random()
    res = {}
    res['res_value']=res_value
    res['param_relevance']=[]
    res['param_relevance'].append({
	'ad_s': random(),
	'ad_d': random(),
	'patient_status': random()
    })
    with open('result/'+str(sys.argv[1])+'.json', 'w') as outfile:
        json.dump(res, outfile)
