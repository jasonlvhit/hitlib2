# -*- coding: utf-8 -*- 

import re
import requests
from BeautifulSoup import BeautifulSoup

BASE_URL = "http://202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=smcx&type=smcx&method1=1&retrieveLib=1"

SM_TYPE="589"
QK_TYPE="590"
LW_TYPE="666"

def query(title ,type="sm") :

	response = requests.get(BASE_URL+"&title=" + title + "&pabookType=" + SM_TYPE);
	html = BeautifulSoup(response.content)
	trs = html.findAll(onmouseover=True)
	booklist = []
	for tr in trs:
		bookinfo = []
		detail = tr.find(onclick=True)['onclick']
		for td in tr.findAll("td"):
			text = td.text.replace("/","")
			text = text.replace("=","")
			text = text.replace("\x1e"," ")
			bookinfo.append(text)
		bookinfo.append(detail)
		booklist.append(bookinfo)

	return booklist
		


#query("运筹学")
