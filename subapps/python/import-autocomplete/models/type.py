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

        sql = "SELECT DISTINCT `type_id` AS `id`, `accommodatie_id` AS `accommodation_id`, `tnaam` AS `name_nl`, " \
              "                `tnaam_de` AS `name_de`, `tnaam_en` AS `name_en`, `tnaam_fr` AS `name_fr`, "        \
              "                `naam` AS `accommodation_name`, `begincode` AS `code` "                             \
              "FROM   `view_accommodatie` "                                                                        \
              "WHERE FIND_IN_SET('%(website)s', `websites`) > 0 "                                                  \
              "AND `atonen` = 1 "                                                                                  \
              "AND `ttonen` = 1 "                                                                                  \
              "AND `atonenzoekformulier` = 1 "                                                                     \
              "AND `ttonenzoekformulier` = 1 "                                                                     \
              "AND `archief` = 0 "                                                                                 \
              "AND `weekendski` = 0 "                                                                              \
              "ORDER BY `naam` ASC"
        
        self.adapter('mysql').execute(sql % {'website': self.website})
        self.data = self.adapter('mysql').fetchall()
        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Type
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.collection()
        data       = []
        order      = 1

        for row in self.data:

            data.append({

                'type': Type.AUTOCOMPLETE_TYPE,
                'type_id': row['id'],
                'locales': ['nl', 'en', 'fr', 'de'],
                'order': order,
                'code': row['code'] + str(row['id']),
                'name': {

                    'nl': row['accommodation_name'] + ' ' + row['name_nl'],
                    'en': row['accommodation_name'] + ' ' + row['name_en'],
                    'de': row['accommodation_name'] + ' ' + row['name_de'],
                    'fr': row['accommodation_name'] + ' ' + row['name_fr']
                }
            })
            
            order += 1

        collection.insert(data)
        return self