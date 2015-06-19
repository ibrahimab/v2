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

        sql = 'SELECT `land_id` AS `id`, `naam` AS `name_nl`, '  \
              '`naam_de` AS `name_de`, `naam_en` AS `name_en`, ' \
              '`naam_fr` AS `name_fr` '                          \
              'FROM   `land` '

        self.adapter('mysql').execute(sql)
        self.data = self.adapter('mysql').fetchall()

        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Country
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.adapter('mongo').autocomplete
        data       = []
        order      = 1

        for row in self.data:

            data.append({

                'type':             Country.AUTOCOMPLETE_TYPE,
                'type_id':          row['id'],
                'locales':          ['nl', 'en', 'fr', 'de'],
                'name':             {

                    'nl': row['name_nl'],
                    'en': row['name_en'],
                    'de': row['name_de'],
                    'fr': row['name_fr']
                },
                'order': order
            })

            order += 1

        collection.insert(data)
        return self