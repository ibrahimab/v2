"""
Type model class

This class fetches all the types from the database
and imports it into the autocomplete collection.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
from base import Base
import sys
class Type(Base):
    """
    @var  AUTOCOMPLETE_TYPE: Autocomplete type to be used when inserting autocomplete data
    @type AUTOCOMPLETE_TYPE: C{string}
    """
    AUTOCOMPLETE_TYPE = 'type'
        
    """
    Fetching types from the MySQL database and saving it for later use.
    @rtype: Type
    """
    def fetch(self):
        
        sql = 'SELECT `type_id` AS `id`, `accommodatie_id` AS `accommodation_id`, `naam` AS `name_nl`, `websites`, ' \
              '`naam_de` AS `name_de`, `naam_en` AS `name_en`, `naam_fr` AS `name_fr`, `zoekvolgorde` AS `order` '   \
              'FROM   `type` '                                                                                       \
              'WHERE  `tonen` = 1 '                                                                                  \
              'AND    `tonenzoekformulier` = 1'

        self.adapter('mysql').execute(sql)
        self.data = self.adapter('mysql').fetchall()
        return self
    
    """
    Indexing the data fetched from MySQL database to mongodb
    
    @rtype: Type
    """
    def insert(self):
        
        if not self.data:
            return self
        
        collection = self.adapter('mongo').autocomplete
        data       = []

        for row in self.data:

            data.append({

                'type':             Type.AUTOCOMPLETE_TYPE,
                'type_id':          row['id'],
                'locales':          ['nl', 'en', 'fr', 'de'],
                'name':             {
                    
                    'nl': row['name_nl'],
                    'en': row['name_en'],
                    'de': row['name_de'],
                    'fr': row['name_fr']
                },
                'websites':         row['websites'].split(','),
                'accommodation_id': row['accommodation_id'],
                'order':            row['order']
            })

        collection.insert(data)
        return self