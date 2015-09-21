"""
Region model class

This class fetches all the regions from the database
and imports it into the autocomplete collection.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
from base import Base
class Region(Base):
    """
    @var  AUTOCOMPLETE_TYPE: Autocomplete type to be used when inserting autocomplete data
    @type AUTOCOMPLETE_TYPE: C{string}
    """
    AUTOCOMPLETE_TYPE = 'region'

    """
    Fetching regions from the MySQL database and saving it for later use.
    @rtype: Region
    """
    def fetch(self):

        sql = "SELECT DISTINCT `skigebied_id` AS `id`, `skigebied` AS `name_nl`, `skigebied_en` AS `name_en`, " \
              "                `skigebied_de` AS `name_de`, `skigebied_fr` AS `name_fr` "                       \
              "FROM   `view_accommodatie` "                                                                     \
              "WHERE FIND_IN_SET('%(website)s', `websites`) > 0 "                                               \
              "AND `atonen` = 1 "                                                                               \
              "AND `ttonen` = 1 "                                                                               \
              "AND `atonenzoekformulier` = 1 "                                                                  \
              "AND `ttonenzoekformulier` = 1 "                                                                  \
              "AND `archief` = 0 "                                                                              \
              "AND `weekendski` = 0 "                                                                           \
              "ORDER BY `skigebied` ASC"

        self.adapter('mysql').execute(sql % {'website': self.website})
        self.data = self.adapter('mysql').fetchall()
        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Region
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.collection()
        data       = []
        order      = 1

        for row in self.data:

            data.append({

                'type': Region.AUTOCOMPLETE_TYPE,
                'type_id': row['id'],
                'locales': ['nl', 'en', 'fr', 'de'],
                'order': order,
                'name': {

                    'nl': row['name_nl'],
                    'en': row['name_en'],
                    'de': row['name_de'],
                    'fr': row['name_fr']
                },
                'searchable': {
                    
                    'nl': self.strip_accents(row['name_nl'].lower()) if isinstance(row['name_nl'], basestring) else row['name_nl'],
                    'en': self.strip_accents(row['name_en'].lower()) if isinstance(row['name_en'], basestring) else row['name_en'],
                    'de': self.strip_accents(row['name_de'].lower()) if isinstance(row['name_de'], basestring) else row['name_de'],
                    'fr': self.strip_accents(row['name_fr'].lower()) if isinstance(row['name_fr'], basestring) else row['name_fr']
                }
            })

            order += 1

        collection.insert(data)
        return self