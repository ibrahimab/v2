"""
This application is an import of all the searchable objects
we have in the database into mongodb to be able to have a
faster autocomplete feature on the new website.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
import sys
import argparse
import MySQLdb as mysql
import pymongo as mongo
import settings

from models.accommodation import Accommodation
from models.type          import Type
from models.country       import Country
from models.region        import Region
from models.place         import Place

parser = argparse.ArgumentParser()
parser.add_argument('--env', dest='env', default='test')

args   = parser.parse_args()

try:

    connection       = settings.connection[args.env]
    mysql_connection = mysql.connect(**connection['mysql'])
    mysql_cursor     = mysql_connection.cursor()

    mongo_client     = mongo.MongoClient(connection['mongo']['uri'])
    mongo_database   = mongo_client[connection['mongo']['db']]

    # clearing old data
    mongo_database.autocomplete.drop()

    accommodations   = Accommodation(mysql_cursor, mongo_database)
    accommodations.fetch().insert()

    types            = Type(mysql_cursor, mongo_database)
    types.fetch().insert()

    countries        = Country(mysql_cursor, mongo_database)
    countries.fetch().insert()

    regions          = Region(mysql_cursor, mongo_database)
    regions.fetch().insert()

    places           = Place(mysql_cursor, mongo_database)
    places.fetch().insert()

except mysql.Error, e:

    print 'Error'
    print e

finally:

    print 'Closing connections'
    mysql_connection.close()
    mongo_client.close()