import csv
import MySQLdb

mydb = MySQLdb.connect(host='localhost', user='root', passwd='', db='db_remboo')
cursor = mydb.cursor()

def create_link():
    cursor.execute("CREATE TABLE iF NOT EXISTS links (movieId INT, imdbId INT, tmdbId INT, PRIMARY KEY (movieId))")
    with open('../ml-latest-small/links.csv', encoding="utf8") as csvf:
        csdata = csv.reader(csvf)
        next(csvf)
        for row in csdata:
            sql = 'INSERT INTO links(movieId,imdbId, tmdbId) VALUES ("%s","%s", "%s");' % (row[0], row[1], row[2])
            print(sql)
            try:
                cursor.execute(sql)
            except:
                print("------------------Ha habido un error------------------")
                print(mydb.error())
                mydb.rollback()

def create_movies():
    cursor.execute("CREATE TABLE iF NOT EXISTS movies(idMovies INT, title VARCHAR(999999), geners VARCHAR(999999),PRIMARY KEY (idMovies))")
    with open('../ml-latest-small/movies.csv', encoding="utf8") as csvf:
        csdata = csv.reader(csvf)
        next(csvf)
        for row in csdata:
            row[0] = str(row[0]).replace('"',"'")
            row[1] = str(row[1]).replace('"',"'")
            row[2] = str(row[2]).replace('"',"'")
            sql = 'INSERT INTO movies(idMovies, title, geners) VALUES ("%s","%s", "%s");' % (row[0], row[1], row[2])
            print(sql)
            try:
                cursor.execute(sql)
            except:
                print("------------------Ha habido un error------------------")
                print(mydb.error())
                mydb.rollback()

def create_tags():
    cursor.execute("CREATE TABLE iF NOT EXISTS tags(user_id INT, movie_id INT, tags VARCHAR(999999), time_stamp VARCHAR(999999))")
    with open('../ml-latest-small/tags.csv',  encoding="utf8") as csvfile:
        next(csvfile)
        csdata = csv.reader(csvfile)
        for row in csdata:
            row[0] = str(row[0]).replace('"',"'")
            row[1] = str(row[1]).replace('"',"'")
            row[2] = str(row[2]).replace('"',"'")
            sql = 'INSERT INTO tags(user_id,movie_id, tags, time_stamp) VALUES ("%s","%s","%s","%s");' % (row[0], row[1], row[2], row[3])
            print(sql)
            try:
                cursor.execute(sql)
            except:
                print("------------------Ha habido un error------------------")
                print(mydb.error())
                mydb.rollback()


def create_ratings():
    cursor.execute("CREATE TABLE iF NOT EXISTS ratings(user_id INT, movie_id INT, ratings decimal(11,2), time_stamp VARCHAR(999999))")
    with open('../ml-latest-small/ratings.csv', newline='',  encoding="utf8") as csvfile:
        next(csvfile)
        csdata = csv.reader(csvfile)
        for row in csdata:
            row[0] = str(row[0]).replace('"',"'")
            row[1] = str(row[1]).replace('"',"'")
            row[2] = str(row[2]).replace('"',"'")
            # Prepare SQL query to INSERT a record into the database.
            sql = 'INSERT INTO ratings(user_id,movie_id, ratings,time_stamp) VALUES ("%s","%s","%s","%s");' % (row[0], row[1], row[2], row[3])
            print(sql)
            try:
                cursor.execute(sql)
            except:
                print("------------------Ha habido un error------------------")
                print(mydb.error())
                mydb.rollback()


mydb.set_character_set('utf8')
cursor.execute('SET NAMES utf8;')
cursor.execute('SET CHARACTER SET utf8;')
cursor.execute('SET character_set_connection=utf8;')

create_link()
create_movies()
create_tags()
create_ratings()

mydb.commit()
mydb.close()

print("Created")
