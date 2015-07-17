"""
Base model class

This is the base class for all the models. It has all
the default methods every model needs.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
import sys
class Base():
    """
    Initializing the model, injecting the MySQL and MongoDB connection dependencies.

    @param mysql: MySQL cursor object
    @type  mysql: L{MySQLdb.cursors.Cursor}
    @param mongo: MongoDB connection object
    @type  mongo: L{pymongo.mongo_client.MongoClient}
    """
    def __init__(self, mysql, mongo, website):
        self.adapters = {'mysql': mysql, 'mongo': mongo}
        self.website  = website
        self.data     = None

    """
    Getting adapter

    @param  adapter: Adapter to be returned
    @type   adapter: C{string}
    @rtype         : L{MySQLdb.cursors.Cursor} or L{pymongo.mongo_client.MongoClient}
    """
    def adapter(self, adapter):
        return self.adapters[adapter]
        
    def collection(self):
        return self.adapter('mongo')['%(website)s.autocomplete' % {'website': self.website}]