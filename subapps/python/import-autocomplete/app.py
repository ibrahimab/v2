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
import MySQLdb as mysql
import pymongo as mongo
import sys
import settings
from models.accommodation import Accommodation
from models.type          import Type
from models.country       import Country
from models.region        import Region
from models.place         import Place

try:

    mysql_connection = mysql.connect(**settings.connection['mysql'])
    mysql_cursor     = mysql_connection.cursor()

    mongo_client     = mongo.MongoClient(settings.connection['mongo']['uri'])
    mongo_database   = mongo_client[settings.connection['mongo']['db']]

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