import requests
import re
from bs4 import BeautifulSoup

"""Método para hacer webscraping en las páginas de themoviedb

Arguments:
    url {string} -- direcciones de la pagina web

Returns:
    array -- url foto y descripcion
"""
url = 11860

enlace = 'https://www.themoviedb.org/movie/'+url
enlace = requests.get(enlace)
soup = BeautifulSoup(enlace.content, 'html.parser')

foto = soup.find_all('a', class_='no_click progressive replace')[0]['href']
descripcion = soup.find_all('div', class_='overview')
for desc in descripcion:
    description = desc.find('p').text

arr = []
arr.append(foto)
arr.append(description)
return arr


