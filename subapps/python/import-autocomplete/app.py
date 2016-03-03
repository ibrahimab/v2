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

from models.type          import Type
from models.country       import Country
from models.region        import Region
from models.place         import Place

parser = argparse.ArgumentParser()
parser.add_argument('-w', dest='website', required=True)
parser.add_argument('--env', dest='env', default='test')

args   = parser.parse_args()

try:

    connection       = settings.connection[args.env]
    mysql_connection = mysql.connect(**connection['mysql'])
    mysql_cursor     = mysql_connection.cursor()

    mongo_client     = mongo.MongoClient(connection['mongo']['uri'])
    mongo_database   = mongo_client[connection['mongo']['db']]

    # clearing old data
    mongo_database[args.website + '.autocomplete'].drop()

    types            = Type(mysql_cursor, mongo_database, args.website)
    types.fetch().insert()

    countries        = Country(mysql_cursor, mongo_database, args.website)
    countries.fetch().insert()

    regions          = Region(mysql_cursor, mongo_database, args.website)
    regions.fetch().insert()

    places           = Place(mysql_cursor, mongo_database, args.website)
    places.fetch().insert()

except mysql.Error, e:

    print 'Error'
    print e

    mysql_connection.close()
    mongo_client.close()