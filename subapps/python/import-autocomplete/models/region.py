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

        sql = 'SELECT `skigebied_id` AS `id`, `naam` AS `name_nl`, `websites`, `wzt` AS `season`, ' \
              '`naam_de` AS `name_de`, `naam_en` AS `name_en`, `naam_fr` AS `name_fr`  '            \
              'FROM   `skigebied` '                                                                 \
              'ORDER BY `naam` ASC'

        self.adapter('mysql').execute(sql)
        self.data = self.adapter('mysql').fetchall()
        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Region
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.adapter('mongo').autocomplete
        data       = []
        order      = 1

        for row in self.data:

            data.append({

                'type':             Region.AUTOCOMPLETE_TYPE,
                'type_id':          row['id'],
                'locales':          ['nl', 'en', 'fr', 'de'],
                'name':             {

                    'nl': row['name_nl'],
                    'en': row['name_en'],
                    'de': row['name_de'],
                    'fr': row['name_fr']
                },
                'websites':         row['websites'].split(','),
                'season':           row['season'],
                'order':            order
            })

            order += 1

        collection.insert(data)
        return self