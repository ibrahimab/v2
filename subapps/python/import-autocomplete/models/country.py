"""
Country model class

This class fetches all the countries from the database
that are searchable and imports it into autocomplete collection in MongoDB.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
from base import Base
from slugify import slugify

class Country(Base):
    """
    @var  AUTOCOMPLETE_TYPE: Autocomplete type to be used when inserting autocomplete data
    @type AUTOCOMPLETE_TYPE: C{string}
    """
    AUTOCOMPLETE_TYPE = 'country'

    """
    Fetching countries from the MySQL database and saving it for later use.
    @rtype: Country
    """
    def fetch(self):

        sql = "SELECT DISTINCT `land_id` AS `id`, `land` AS `name_nl`, `land_en` AS `name_en`, " \
              "`land_de` AS `name_de`, `land_fr` AS `name_fr` "                                  \
              "FROM   `view_accommodatie` "                                                      \
              "WHERE FIND_IN_SET('%(website)s', `websites`) > 0 "                                \
              "AND `atonen` = 1 "                                                                \
              "AND `ttonen` = 1 "                                                                \
              "AND `atonenzoekformulier` = 1 "                                                   \
              "AND `ttonenzoekformulier` = 1 "                                                   \
              "AND `weekendski` = 0 "                                                            \
              "AND `archief` = 0 "                                                               \
              "ORDER BY `land` ASC"

        self.adapter('mysql').execute(sql % {'website': self.website})
        self.data = self.adapter('mysql').fetchall()

        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Country
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.collection()
        data       = []
        order      = 1

        for row in self.data:

            data.append({

                'type': Country.AUTOCOMPLETE_TYPE,
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

                    'nl': self.normalize(row['name_nl']),
                    'en': self.normalize(row['name_en']),
                    'de': self.normalize(row['name_de']),
                    'fr': self.normalize(row['name_fr'])
                },
                'search_term': {

                    'nl': slugify(row['name_nl']),
                    'en': slugify(row['name_en']),
                    'de': slugify(row['name_de']),
                    'fr': slugify(row['name_fr'])
                }
            })

            order += 1

        collection.insert(data)
        return self
