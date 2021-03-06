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
import unicodedata
import re

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

    def strip_accents(self, string):
        return unicodedata.normalize('NFKD', string).encode('ascii', 'ignore') if isinstance(string, unicode) else string

    def normalize(self, string):

        # stripping special characters from string
        normalized = self.strip_accents(string.lower()) if isinstance(string, basestring) else string

        # replace non-alphanumeric characters with spaces
        pattern    = re.compile('[^\da-z]', re.IGNORECASE)
        normalized = pattern.sub(' ', normalized)

        # convert to lowercase
        normalized = normalized.lower()

        # multiple spaces to a single one
        normalized = re.sub(' {2,}', ' ', normalized)

        return normalized
